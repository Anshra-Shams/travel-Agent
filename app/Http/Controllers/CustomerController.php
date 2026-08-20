<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerActivity;
use App\Models\CustomerFollowUp;
use App\Models\CustomerService;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index(Request $request)
    {
        $customers = Customer::query()
            ->with('lead')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('cnic', 'like', "%{$search}%")
                        ->orWhere('passport_number', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('customer_source'), function ($query) use ($request) {
                $query->where('customer_source', $request->customer_source);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('service'), function ($query) use ($request) {
                $query->whereJsonContains('service', $request->service);
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', [
            'customers' => $customers,
            'services' => ServiceType::getActiveNames(),
            'filters' => $request->only(['search', 'customer_source', 'status', 'service']),
        ]);
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('customers.create', [
            'services' => ServiceType::getActiveNames(),
        ]);
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['service'] = $this->normalizeServices($data['service'] ?? []);
        $data['customer_source'] = 'direct';
        $data['status'] = $request->input('status', 'Active');

        $customer = Customer::create($data);

        $customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'system',
            'description' => 'Customer created directly',
        ]);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $customer->load([
            'lead',
            'followUps.agent',
            'activities.agent',
            'services',
        ]);

        return view('customers.show', [
            'customer' => $customer,
            'services' => ServiceType::getActiveNames(),
        ]);
    }

    /**
     * Return the services associated with a customer as JSON.
     */
    public function services(Customer $customer)
    {
        return response()->json([
            'services' => $customer->services_list,
        ]);
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', [
            'customer' => $customer,
            'services' => ServiceType::getActiveNames(),
        ]);
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer)
    {
        $data = $this->validated($request);
        $data['service'] = $this->normalizeServices($data['service'] ?? []);

        $oldStatus = $customer->status;

        $customer->update($data);

        if ($customer->wasChanged('status') && $oldStatus !== $customer->status) {
            $customer->activities()->create([
                'agent_id' => auth()->id(),
                'type' => 'status',
                'description' => "Status changed from {$oldStatus} to {$customer->status}",
            ]);
        } else {
            $customer->activities()->create([
                'agent_id' => auth()->id(),
                'type' => 'note',
                'description' => 'Customer details updated',
            ]);
        }

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Add a follow-up to a customer.
     */
    public function storeFollowUp(Request $request, Customer $customer)
    {
        $request->validate([
            'note' => ['required', 'string', 'max:2000'],
            'follow_up_date' => ['nullable', 'date'],
        ]);

        $customer->followUps()->create([
            'agent_id' => auth()->id(),
            'note' => $request->note,
            'follow_up_date' => $request->follow_up_date,
        ]);

        $customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'follow-up',
            'description' => 'Follow-up scheduled: ' . ($request->follow_up_date ?: 'not scheduled'),
        ]);

        return back()->with('success', 'Follow-up added successfully.');
    }

    /**
     * Mark a follow-up as completed.
     */
    public function completeFollowUp(CustomerFollowUp $followUp)
    {
        $followUp->update(['completed_at' => now()]);

        $followUp->customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'follow-up',
            'description' => 'Follow-up marked as completed',
        ]);

        return back()->with('success', 'Follow-up marked as completed.');
    }

    /**
     * Validate and prepare the customer request data.
     */
    protected function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'cnic' => ['nullable', 'string', 'max:30'],
            'passport_number' => ['nullable', 'string', 'max:50'],
            'passport_expiry' => ['nullable', 'date'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
            'address' => ['nullable', 'string', 'max:1000'],
            'country' => ['nullable', 'string', 'max:255'],
            'service' => ['nullable', 'array'],
            'service.*' => ['in:' . implode(',', ServiceType::getActiveNames())],
            'destination' => ['nullable', 'string', 'max:255'],
            'travel_date' => ['nullable', 'date'],
            'travelers' => ['nullable', 'integer', 'min:1', 'max:999'],
            'source' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:Active,Inactive'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);
    }

    /**
     * Normalize the submitted services into a clean array.
     */
    protected function normalizeServices(array $services): array
    {
        return array_values(array_filter(
            $services,
            fn ($service) => is_string($service) && trim($service) !== ''
        ));
    }
}
