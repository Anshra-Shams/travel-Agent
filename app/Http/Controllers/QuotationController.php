<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Customer;
use App\Models\CustomerService;
use App\Models\Quotation;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::with(['customer'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(function ($c) use ($s) {
                    $c->where('quotation_number', 'like', "%{$s}%")
                        ->orWhereHas('customer', fn ($cu) => $cu
                            ->where('name', 'like', "%{$s}%")
                            ->orWhere('phone', 'like', "%{$s}%")
                        );
                });
            })
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('id');

        $quotations = $query->paginate(15)->withQueryString();

        $type = $request->filled('type') ? $request->type : null;
        $statuses = $type === 'booking'
            ? Quotation::BOOKING_STATUSES
            : Quotation::STATUSES;

        return view('quotations.index', [
            'quotations' => $quotations,
            'serviceTypes' => ServiceType::getActiveNames(),
            'statuses' => $statuses,
            'paymentStatuses' => Quotation::PAYMENT_STATUSES,
            'filters' => $request->only(['search', 'type', 'status']),
        ]);
    }

    public function create(Request $request)
    {
        return view('quotations.create', [
            'quotation' => null,
            'customers' => Customer::orderBy('name')->get(['id', 'name', 'phone', 'travelers']),
            'serviceTypes' => ServiceType::getActiveNames(),
            'serviceAmounts' => ServiceType::whereNotNull('amount')
                ->pluck('amount', 'name')
                ->map(fn ($v) => (float) $v)
                ->toArray(),
            'accounts' => Account::active()->orderBy('name')->get(),
            'paymentStatuses' => Quotation::PAYMENT_STATUSES,
            'bookingStatuses' => Quotation::BOOKING_STATUSES,
            'quotationStatuses' => Quotation::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $type = $data['type'];

        $requirement = $data['customer_service_id'] ?? null
            ? CustomerService::find($data['customer_service_id'])
            : null;

        $quotation = Quotation::create([
            'type' => $type,
            'customer_id' => $data['customer_id'],
            'customer_service_id' => $data['customer_service_id'] ?? null,
            'agent_id' => auth()->id(),
            'account_id' => $data['account_id'] ?? null,
            'service_type' => $data['services'][0] ?? 'General',
            'services' => $data['services'],
            'destination' => $data['destination'] ?? $requirement?->destination ?? null,
            'quotation_date' => now()->toDateString(),
            'valid_until' => $type === 'quotation' ? $data['valid_until'] : null,
            'travel_date' => $type === 'booking'
                ? ($data['travel_date'] ?? $requirement?->travel_date ?? null)
                : null,
            'status' => $data['status'],
            'payment_status' => $type === 'booking' ? ($data['payment_status'] ?? 'Pending') : null,
            'reference' => $type === 'quotation' ? ($data['reference'] ?? null) : null,
            'subtotal' => $data['subtotal'],
            'discount' => $data['discount'] ?? 0,
            'tax' => 0,
            'grand_total' => $data['grand_total'],
            'notes' => $data['notes'] ?? null,
        ]);

        $quotation->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => $type === 'booking'
                ? "Booking {$quotation->quotation_number} created"
                : "Quotation {$quotation->quotation_number} created",
        ]);

        $quotation->customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => ($type === 'booking' ? 'Booking ' : 'Quotation ') . "{$quotation->quotation_number} created",
        ]);

        if ($type === 'booking') {
            $this->recordPayment($quotation, $data);
        }

        return redirect()
            ->route('quotations.show', $quotation)
            ->with('success', ($type === 'booking' ? 'Booking' : 'Quotation') . ' created successfully.');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['customer', 'agent', 'activities.agent']);

        return view('quotations.show', [
            'quotation' => $quotation,
        ]);
    }

    public function requirement(Request $request)
    {
        $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'service' => ['required', Rule::in(ServiceType::getActiveNames())],
        ]);

        $requirement = CustomerService::where('customer_id', $request->customer_id)
            ->where('service_type', $request->service)
            ->latest()
            ->first();

        $customer = Customer::with('services')->findOrFail($request->customer_id);

        $allServices = $customer->services
            ->sortByDesc('updated_at')
            ->map(function (CustomerService $cs) use ($request) {
                return [
                    'id' => $cs->id,
                    'service_type' => $cs->service_type,
                    'status' => $cs->status,
                    'destination' => $cs->destination,
                    'travel_date' => $cs->travel_date?->format('Y-m-d'),
                    'travelers' => $cs->travelers,
                    'requirements' => $cs->requirements,
                    'general' => $this->generalInfo($cs),
                    'specific' => $cs->specific_requirements,
                    'total' => (float) (ServiceType::where('name', $cs->service_type)->value('amount') ?? 0),
                    'selected' => $cs->service_type === $request->service,
                ];
            })
            ->values();

        return response()->json([
            'found' => $requirement !== null,
            'amount' => $requirement
                ? (float) (ServiceType::where('name', $requirement->service_type)->value('amount') ?? 0)
                : 0,
            'requirement' => $requirement ? [
                'id' => $requirement->id,
                'service_type' => $requirement->service_type,
                'status' => $requirement->status,
                'destination' => $requirement->destination,
                'travel_date' => $requirement->travel_date?->format('Y-m-d'),
                'travelers' => $requirement->travelers,
                'requirements' => $requirement->requirements,
                'general' => $this->generalInfo($requirement),
                'specific' => $requirement->specific_requirements,
            ] : null,
            'all_services' => $allServices,
            'customer_travelers' => (int) ($customer->travelers ?: 0),
        ]);
    }

    public function edit(Quotation $quotation)
    {
        $quotation->load('customer');

        return view('quotations.create', [
            'quotation' => $quotation,
            'customers' => Customer::orderBy('name')->get(['id', 'name', 'phone', 'travelers']),
            'serviceTypes' => ServiceType::getActiveNames(),
            'serviceAmounts' => ServiceType::whereNotNull('amount')
                ->pluck('amount', 'name')
                ->map(fn ($v) => (float) $v)
                ->toArray(),
            'accounts' => Account::active()->orderBy('name')->get(),
            'paymentStatuses' => Quotation::PAYMENT_STATUSES,
            'bookingStatuses' => Quotation::BOOKING_STATUSES,
            'quotationStatuses' => Quotation::STATUSES,
        ]);
    }

    public function update(Request $request, Quotation $quotation)
    {
        $data = $this->validated($request);
        $type = $data['type'];

        $requirement = $data['customer_service_id'] ?? null
            ? CustomerService::find($data['customer_service_id'])
            : null;

        $quotation->update([
            'type' => $type,
            'customer_id' => $data['customer_id'],
            'customer_service_id' => $data['customer_service_id'] ?? null,
            'service_type' => $data['services'][0] ?? $quotation->service_type ?? 'General',
            'services' => $data['services'],
            'destination' => $data['destination'] ?? $requirement?->destination ?? null,
            'valid_until' => $type === 'quotation' ? $data['valid_until'] : null,
            'travel_date' => $type === 'booking'
                ? ($data['travel_date'] ?? $requirement?->travel_date ?? null)
                : null,
            'status' => $data['status'],
            'payment_status' => $type === 'booking' ? ($data['payment_status'] ?? 'Pending') : null,
            'reference' => $type === 'quotation' ? ($data['reference'] ?? null) : null,
            'subtotal' => $data['subtotal'],
            'discount' => $data['discount'] ?? 0,
            'grand_total' => $data['grand_total'],
            'notes' => $data['notes'] ?? null,
        ]);

        if ($type === 'booking') {
            $this->recordPayment($quotation, $data);
        }

        $quotation->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => ($quotation->is_booking ? 'Booking' : 'Quotation') . ' details updated',
        ]);

        return redirect()
            ->route('quotations.show', $quotation)
            ->with('success', 'Record updated successfully.');
    }

    public function destroy(Quotation $quotation)
    {
        $label = $quotation->is_booking ? 'Booking' : 'Quotation';

        $quotation->customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => "{$label} {$quotation->quotation_number} deleted",
        ]);

        $quotation->items()->delete();
        $quotation->activities()->delete();
        $quotation->delete();

        return redirect()
            ->route('quotations.index')
            ->with('success', "{$label} deleted successfully.");
    }

    public function convertToBooking(Quotation $quotation)
    {
        if ($quotation->type === 'booking') {
            return back()->with('info', 'This record is already a booking.');
        }

        $quotation->update([
            'type' => 'booking',
            'status' => 'Processing',
            'payment_status' => $quotation->payment_status ?: 'Pending',
            'valid_until' => null,
            'reference' => null,
        ]);

        $quotation->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => "Quotation {$quotation->quotation_number} converted to booking {$quotation->quotation_number}",
        ]);

        return back()->with('success', 'Quotation converted to booking successfully.');
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $allowed = $quotation->is_booking ? Quotation::BOOKING_STATUSES : Quotation::STATUSES;

        $data = $request->validate([
            'status' => ['required', Rule::in($allowed)],
        ]);

        $oldStatus = $quotation->status;
        $newStatus = $data['status'];

        $quotation->update(['status' => $newStatus]);

        $quotation->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'status',
            'description' => "Status changed from {$oldStatus} to {$newStatus}",
        ]);

        return back()->with('success', "Status changed to {$newStatus}.");
    }

    /**
     * Record a booking payment against the selected account in the ledger.
     * If an account was changed or the amount changed, keep one entry per save.
     */
    protected function recordPayment(Quotation $quotation, array $data): void
    {
        $accountId = $data['account_id'] ?? null;
        $paidTotal = (float) ($data['payment_amount'] ?? 0);

        if ($paidTotal > 0) {
            if ($accountId) {
                $account = Account::find($accountId);
                if ($account) {
                    AccountTransaction::create([
                        'account_id' => $accountId,
                        'date' => now()->toDateString(),
                        'reference' => $quotation->quotation_number,
                        'description' => "Payment received from {$quotation->customer->name} - {$quotation->quotation_number}",
                        'credit' => $paidTotal,
                        'debit' => 0,
                    ]);

                    $balance = $account->opening_balance + $account->transactions()->sum('credit') - $account->transactions()->sum('debit');
                    $account->update(['current_balance' => $balance]);
                }
            }

            $paymentStatus = $paidTotal >= (float) $quotation->grand_total ? 'Paid' : 'Partial';
            $quotation->update([
                'payment_status' => $paymentStatus,
                'deposit_required' => $paidTotal,
                'remaining_amount' => max(0, (float) $quotation->grand_total - $paidTotal),
            ]);
        }
    }

    /**
     * Validate shared + type-specific fields and return prepared data.
     */
    protected function validated(Request $request): array
    {
        $serviceTypeNames = implode(',', ServiceType::getActiveNames());

        $data = $request->validate([
            'type' => ['required', Rule::in(['booking', 'quotation'])],
            'customer_id' => ['required', 'exists:customers,id'],
            'customer_service_id' => ['nullable', 'exists:customer_services,id'],
            'service' => ['required', 'string', 'in:' . $serviceTypeNames],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'grand_total' => ['required', 'numeric', 'min:0'],
            'destination' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:3000'],
            // Booking specific
            'travel_date' => ['nullable', 'date'],
            'payment_status' => ['nullable', Rule::in(Quotation::PAYMENT_STATUSES)],
            'account_id' => ['nullable', 'exists:accounts,id'],
            'payment_amount' => ['nullable', 'numeric', 'min:0'],
            'advance' => ['nullable', 'numeric', 'min:0'],
            // Quotation specific
            'valid_until' => ['nullable', 'date'],
            'reference' => ['nullable', 'string', 'max:255'],
        ]);

        $data['services'] = [$data['service']];
        unset($data['service']);

        $type = $data['type'];

        if ($type === 'booking') {
            $status = $request->validate([
                'status' => ['required', Rule::in(Quotation::BOOKING_STATUSES)],
            ])['status'];
            $data['status'] = $status;
        } else {
            $status = $request->validate([
                'status' => ['required', Rule::in(Quotation::STATUSES)],
            ])['status'];
            $data['status'] = $status;

            if (empty($data['valid_until'])) {
                $request->validate([
                    'valid_until' => ['required', 'date', 'after_or_equal:today'],
                ]);
            }
        }

        return $data;
    }

    /**
     * Build the "General Information" block for a customer service.
     */
    protected function generalInfo(CustomerService $service): array
    {
        return [
            ['label' => 'Customer', 'value' => $service->customer?->name],
            ['label' => 'Service Type', 'value' => $service->service_type],
            ['label' => 'Destination', 'value' => $service->destination],
            ['label' => 'Travel Date', 'value' => $service->travel_date?->format('d M Y')],
            ['label' => 'Number of Travelers', 'value' => $service->travelers],
        ];
    }
}