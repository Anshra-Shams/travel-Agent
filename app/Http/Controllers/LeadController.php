<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Customer;
use App\Models\CustomerService;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    public static function getServices(): array
    {
        return \App\Models\ServiceType::where('status', 'active')->orderBy('sort_order')->pluck('name')->toArray();
    }

    public const STATUSES = [
        'New',
        'Contacted',
        'Interested',
        'Quotation Sent',
        'Converted',
        'Not Interested',
        'Follow-up',
    ];

    /**
     * Display a listing of the leads.
     */
    public function index(Request $request)
    {
        $leads = Lead::query()
            ->with(['assignedAgent'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('destination', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('service'), function ($query) use ($request) {
                $query->where('service', $request->service);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('agent'), function ($query) use ($request) {
                $query->where('agent_id', $request->agent);
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('leads.index', [
            'leads' => $leads,
            'services' => self::getServices(),
            'statuses' => self::STATUSES,
            'agents' => User::orderBy('name')->get(),
            'filters' => $request->only(['search', 'service', 'status', 'agent']),
        ]);
    }

    /**
     * Show the form for creating a new lead.
     */
    public function create()
    {
        return view('leads.create', [
            'services' => self::getServices(),
            'statuses' => self::STATUSES,
            'agents' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created lead.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);

        $lead = Lead::create($data);

        $this->logActivity($lead, 'system', "Lead created with status: {$lead->status}");

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead created successfully.');
    }

    /**
     * Display the specified lead.
     */
    public function show(Lead $lead)
    {
        $lead->load([
            'assignedAgent',
            'customer',
            'followUps.agent',
            'activities.agent',
        ]);

        return view('leads.show', [
            'lead' => $lead,
            'services' => self::getServices(),
            'statuses' => self::STATUSES,
            'agents' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified lead.
     */
    public function edit(Lead $lead)
    {
        return view('leads.edit', [
            'lead' => $lead,
            'services' => self::getServices(),
            'statuses' => self::STATUSES,
            'agents' => User::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified lead.
     */
    public function update(Request $request, Lead $lead)
    {
        $data = $this->validated($request);

        $oldStatus = $lead->status;

        $lead->update($data);

        if ($lead->wasChanged('status') && $oldStatus !== $lead->status) {
            $this->logActivity($lead, 'status', "Status changed from {$oldStatus} to {$lead->status}");
        } else {
            $this->logActivity($lead, 'note', 'Lead details updated');
        }

        return redirect()
            ->route('leads.show', $lead)
            ->with('success', 'Lead updated successfully.');
    }

    /**
     * Remove the specified lead.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    /**
     * Change the status of a lead.
     */
    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate(['status' => ['required', 'in:' . implode(',', self::STATUSES)]]);

        $oldStatus = $lead->status;
        $lead->update(['status' => $request->status]);

        if ($oldStatus !== $lead->status) {
            $this->logActivity($lead, 'status', "Status changed from {$oldStatus} to {$lead->status}");
        }

        return back()->with('success', "Lead status changed to {$lead->status}.");
    }

    /**
     * Assign or reassign an agent to a lead.
     */
    public function assignAgent(Request $request, Lead $lead)
    {
        $request->validate(['agent_id' => ['nullable', 'exists:users,id']]);

        $oldAgent = $lead->assignedAgent?->name;

        $lead->update(['agent_id' => $request->agent_id ?: null]);

        $newAgent = $lead->assignedAgent?->name;

        $this->logActivity(
            $lead,
            'assignment',
            "Agent assigned: {$oldAgent} → " . ($newAgent ?: 'Unassigned')
        );

        return back()->with('success', 'Lead assigned to ' . ($newAgent ?: 'Unassigned') . '.');
    }

    /**
     * Add a follow-up to a lead.
     */
    public function storeFollowUp(Request $request, Lead $lead)
    {
        $request->validate([
            'note' => ['required', 'string', 'max:2000'],
            'follow_up_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:' . implode(',', self::STATUSES)],
        ]);

        $lead->followUps()->create([
            'agent_id' => auth()->id(),
            'note' => $request->note,
            'status' => $request->status,
            'follow_up_date' => $request->follow_up_date,
        ]);

        $updateData = [];
        if ($request->filled('follow_up_date')) {
            $updateData['follow_up_date'] = $request->follow_up_date;
        }
        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }
        if ($updateData) {
            $lead->update($updateData);
        }

        $this->logActivity($lead, 'follow-up', 'Follow-up scheduled: ' . ($request->follow_up_date ?: 'not scheduled'));

        return back()->with('success', 'Follow-up added successfully.');
    }

    /**
     * Mark a follow-up as completed.
     */
    public function completeFollowUp(FollowUp $followUp)
    {
        $followUp->update(['completed_at' => now()]);

        $this->logActivity($followUp->lead, 'follow-up', 'Follow-up marked as completed');

        return back()->with('success', 'Follow-up marked as completed.');
    }

    /**
     * Convert a lead to a customer without creating duplicates.
     */
    public function convertToCustomer(Lead $lead)
    {
        if ($lead->customer) {
            return back()->with('error', 'This lead has already been converted to a customer.');
        }

        $customer = DB::transaction(function () use ($lead) {
            $customer = Customer::create([
                'lead_id' => $lead->id,
                'name' => $lead->full_name,
                'phone' => $lead->phone,
                'email' => $lead->email,
                'whatsapp' => $lead->whatsapp,
                'service' => $lead->service ? [$lead->service] : [],
                'destination' => $lead->destination,
                'travel_date' => $lead->travel_date,
                'travelers' => $lead->travelers,
                'source' => $lead->source,
                'notes' => $lead->notes,
                'status' => 'Active',
                'customer_source' => 'lead',
            ]);

            $lead->update(['status' => 'Converted']);

            return $customer;
        });

        $this->logActivity($lead, 'conversion', 'Lead converted to customer');

        $customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'conversion',
            'description' => "Customer created by converting lead #{$lead->id}",
        ]);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Lead converted to customer successfully.');
    }

    /**
     * Validate and prepare the lead request data.
     */
    protected function validated(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'service' => ['required', 'in:' . implode(',', self::getServices())],
            'destination' => ['nullable', 'string', 'max:255'],
            'travel_date' => ['nullable', 'date'],
            'travelers' => ['nullable', 'integer', 'min:1', 'max:999'],
            'source' => ['nullable', 'string', 'max:255'],
            'agent_id' => ['nullable', 'exists:users,id'],
            'status' => ['nullable', 'in:' . implode(',', self::STATUSES)],
            'follow_up_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);
    }

    /**
     * Write an activity log entry for a lead.
     */
    protected function logActivity(Lead $lead, string $type, string $description): void
    {
        $lead->activities()->create([
            'agent_id' => auth()->id(),
            'type' => $type,
            'description' => $description,
        ]);
    }
}
