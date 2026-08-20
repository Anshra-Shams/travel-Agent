<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerService;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $query = Quotation::with(['customer', 'serviceRequirement'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->whereHas('customer', fn ($c) => $c
                    ->where('name', 'like', "%{$s}%")
                    ->orWhere('phone', 'like', "%{$s}%")
                );
            })
            ->when($request->filled('service_type'), fn ($q) => $q->where('service_type', $request->service_type))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('quotation_date', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('quotation_date', '<=', $request->date_to))
            ->orderByDesc('id');

        $quotations = $query->paginate(15)->withQueryString();

        return view('quotations.index', [
            'quotations' => $quotations,
            'serviceTypes' => ServiceType::getActiveNames(),
            'statuses' => Quotation::STATUSES,
            'filters' => $request->only(['search', 'service_type', 'status', 'date_from', 'date_to']),
        ]);
    }

    public function create(Request $request)
    {
        $requirementId = $request->query('requirement');
        $requirement = null;

        if ($requirementId) {
            $requirement = CustomerService::with('customer')->find($requirementId);
        }

        if (!$requirement) {
            $requirements = CustomerService::with('customer')
                ->where('status', 'Ready for Quotation')
                ->whereDoesntHave('quotations')
                ->orderByDesc('id')
                ->get();

            return view('quotations.select-requirement', [
                'requirements' => $requirements,
            ]);
        }

        $existingItems = [];
        if ($requirement->service_type === 'Umrah' || $requirement->service_type === 'Hajj') {
            if ($requirement->ticket_requirement) {
                $existingItems[] = ['description' => 'Flight Ticket', 'quantity' => $requirement->travelers ?? 1, 'unit_price' => 0];
            }
            if ($requirement->visa_requirement) {
                $existingItems[] = ['description' => 'Visa', 'quantity' => $requirement->travelers ?? 1, 'unit_price' => 0];
            }
            if ($requirement->makkah_hotel) {
                $existingItems[] = ['description' => 'Makkah Hotel - ' . $requirement->makkah_hotel, 'quantity' => 1, 'unit_price' => 0];
            }
            if ($requirement->madinah_hotel) {
                $existingItems[] = ['description' => 'Madinah Hotel - ' . $requirement->madinah_hotel, 'quantity' => 1, 'unit_price' => 0];
            }
            if ($requirement->transport_requirement) {
                $existingItems[] = ['description' => 'Transportation', 'quantity' => 1, 'unit_price' => 0];
            }
        } elseif ($requirement->service_type === 'Flight Ticket') {
            $existingItems[] = ['description' => 'Flight Ticket', 'quantity' => $requirement->passenger_count ?? $requirement->travelers ?? 1, 'unit_price' => 0];
        } elseif ($requirement->service_type === 'Visa') {
            $existingItems[] = ['description' => 'Visa', 'quantity' => $requirement->applicants ?? $requirement->travelers ?? 1, 'unit_price' => 0];
        } elseif ($requirement->service_type === 'Hotel') {
            $existingItems[] = ['description' => 'Hotel Booking', 'quantity' => $requirement->rooms ?? 1, 'unit_price' => 0];
        } elseif ($requirement->service_type === 'Transportation') {
            $existingItems[] = ['description' => 'Transportation - ' . ($requirement->vehicle_type ?? 'Standard'), 'quantity' => 1, 'unit_price' => 0];
        } else {
            $existingItems[] = ['description' => $requirement->service_type, 'quantity' => $requirement->travelers ?? 1, 'unit_price' => 0];
        }

        if (empty($existingItems)) {
            $existingItems[] = ['description' => '', 'quantity' => 1, 'unit_price' => 0];
        }

        $serviceTypes = ServiceType::getActiveNames();

        return view('quotations.create', [
            'requirement' => $requirement,
            'existingItems' => $existingItems,
            'serviceTypes' => $serviceTypes,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_service_id' => ['required', 'exists:customer_services,id'],
            'service_type' => ['required', 'string'],
            'destination' => ['nullable', 'string', 'max:255'],
            'quotation_date' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after_or_equal:quotation_date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'payment_terms' => ['nullable', 'string', 'max:2000'],
            'deposit_required' => ['nullable', 'numeric', 'min:0'],
            'payment_due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'terms_conditions' => ['nullable', 'string', 'max:5000'],
        ]);

        $requirement = CustomerService::findOrFail($data['customer_service_id']);

        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $discount = $data['discount'] ?? 0;
        $tax = $data['tax'] ?? 0;
        $grandTotal = $subtotal - $discount + $tax;
        $deposit = $data['deposit_required'] ?? null;
        $remaining = $deposit !== null ? max(0, $grandTotal - $deposit) : null;

        $quotation = Quotation::create([
            'customer_id' => $requirement->customer_id,
            'agent_id' => auth()->id(),
            'customer_service_id' => $requirement->id,
            'service_type' => $data['service_type'],
            'destination' => $data['destination'] ?? $requirement->destination,
            'quotation_date' => $data['quotation_date'],
            'valid_until' => $data['valid_until'],
            'status' => 'Draft',
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'grand_total' => $grandTotal,
            'payment_terms' => $data['payment_terms'] ?? null,
            'deposit_required' => $deposit,
            'remaining_amount' => $remaining,
            'payment_due_date' => $data['payment_due_date'] ?? null,
            'notes' => $data['notes'] ?? null,
            'terms_conditions' => $data['terms_conditions'] ?? null,
        ]);

        foreach ($data['items'] as $index => $item) {
            $quotation->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
                'sort_order' => $index,
            ]);
        }

        $quotation->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => 'Quotation created',
        ]);

        $requirement->update(['status' => 'Ready for Quotation']);

        $requirement->customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => "Quotation {$quotation->quotation_number} created for {$quotation->service_type}",
        ]);

        return redirect()
            ->route('quotations.show', $quotation)
            ->with('success', 'Quotation created successfully.');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['customer', 'agent', 'items', 'activities.agent', 'serviceRequirement']);

        return view('quotations.show', [
            'quotation' => $quotation,
        ]);
    }

    public function edit(Quotation $quotation)
    {
        $quotation->load(['customer', 'items', 'serviceRequirement']);
        $serviceTypes = ServiceType::getActiveNames();

        return view('quotations.edit', [
            'quotation' => $quotation,
            'serviceTypes' => $serviceTypes,
        ]);
    }

    public function update(Request $request, Quotation $quotation)
    {
        $data = $request->validate([
            'service_type' => ['required', 'string'],
            'destination' => ['nullable', 'string', 'max:255'],
            'quotation_date' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after_or_equal:quotation_date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'payment_terms' => ['nullable', 'string', 'max:2000'],
            'deposit_required' => ['nullable', 'numeric', 'min:0'],
            'payment_due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'terms_conditions' => ['nullable', 'string', 'max:5000'],
        ]);

        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $discount = $data['discount'] ?? 0;
        $tax = $data['tax'] ?? 0;
        $grandTotal = $subtotal - $discount + $tax;
        $deposit = $data['deposit_required'] ?? null;
        $remaining = $deposit !== null ? max(0, $grandTotal - $deposit) : null;

        $quotation->update([
            'service_type' => $data['service_type'],
            'destination' => $data['destination'] ?? $quotation->destination,
            'quotation_date' => $data['quotation_date'],
            'valid_until' => $data['valid_until'],
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'grand_total' => $grandTotal,
            'payment_terms' => $data['payment_terms'] ?? null,
            'deposit_required' => $deposit,
            'remaining_amount' => $remaining,
            'payment_due_date' => $data['payment_due_date'] ?? null,
            'notes' => $data['notes'] ?? null,
            'terms_conditions' => $data['terms_conditions'] ?? null,
        ]);

        $quotation->items()->delete();
        foreach ($data['items'] as $index => $item) {
            $quotation->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
                'sort_order' => $index,
            ]);
        }

        $quotation->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => 'Quotation updated',
        ]);

        return redirect()
            ->route('quotations.show', $quotation)
            ->with('success', 'Quotation updated successfully.');
    }

    public function destroy(Quotation $quotation)
    {
        $quotation->customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => "Quotation {$quotation->quotation_number} deleted",
        ]);

        $quotation->items()->delete();
        $quotation->activities()->delete();
        $quotation->delete();

        return redirect()
            ->route('quotations.index')
            ->with('success', 'Quotation deleted successfully.');
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', Quotation::STATUSES)],
        ]);

        $oldStatus = $quotation->status;
        $newStatus = $data['status'];

        $quotation->update(['status' => $newStatus]);

        $quotation->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'status',
            'description' => "Status changed from {$oldStatus} to {$newStatus}",
        ]);

        $quotation->customer->activities()->create([
            'agent_id' => auth()->id(),
            'type' => 'note',
            'description' => "Quotation {$quotation->quotation_number} status: {$newStatus}",
        ]);

        return back()->with('success', "Quotation marked as {$newStatus}.");
    }
}
