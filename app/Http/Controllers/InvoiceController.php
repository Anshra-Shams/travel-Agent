<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with([
            'customer',
            'serviceType',
            'package',
        ])->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($customerQuery) use ($search) {
                        $customerQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(15)->withQueryString();

        return view('invoices.index', [
            'invoices' => $invoices,
            'statuses' => [
                'draft',
                'sent',
                'partial',
                'paid',
                'cancelled',
            ],
        ]);
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        $serviceTypes = ServiceType::with([
            'packages' => function ($query) {
                $query->where('status', 'active')
                    ->latest();
            }
        ])
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return view('invoices.create', compact(
            'customers',
            'serviceTypes'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => [
                'required',
                'exists:customers,id',
            ],

            'service_type_id' => [
                'required',
                'exists:service_types,id',
            ],

            'package_id' => [
                'required',
                'exists:packages,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'paid_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'status' => [
                'required',
                'in:draft,sent,partial,paid,cancelled',
            ],

            'issued_at' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $package = \App\Models\Package::where('id', $data['package_id'])
            ->where('service_type_id', $data['service_type_id'])
            ->firstOrFail();

        $quantity = (int) $data['quantity'];

        $unitPrice = (float) ($package->final_price ?? 0);

        if ($unitPrice <= 0) {
            $unitPrice = (float) ($package->original_price ?? 0);
        }

        $subtotal = $unitPrice * $quantity;

        $discount = (float) ($data['discount'] ?? 0);

        if ($discount > $subtotal) {
            return back()
                ->withErrors([
                    'discount' => 'Discount cannot be greater than subtotal.'
                ])
                ->withInput();
        }

        $grandTotal = max(0, $subtotal - $discount);

        $paidAmount = (float) ($data['paid_amount'] ?? 0);

        if ($paidAmount > $grandTotal) {
            return back()
                ->withErrors([
                    'paid_amount' => 'Paid amount cannot be greater than grand total.'
                ])
                ->withInput();
        }

        $status = $data['status'];

        if ($paidAmount >= $grandTotal && $grandTotal > 0) {
            $status = 'paid';
        } elseif ($paidAmount > 0) {
            $status = 'partial';
        }

        $invoice = DB::transaction(function () use (
            $data,
            $unitPrice,
            $subtotal,
            $discount,
            $grandTotal,
            $paidAmount,
            $status
        ) {
            $invoiceNumber = $this->generateInvoiceNumber();

            return Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $data['customer_id'],
                'service_type_id' => $data['service_type_id'],
                'package_id' => $data['package_id'],
                'quantity' => $data['quantity'],
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'grand_total' => $grandTotal,
                'paid_amount' => $paidAmount,
                'currency' => $data['currency'],
                'status' => $status,
                'issued_at' => $data['issued_at'] ?? now()->toDateString(),
                'notes' => $data['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load([
            'customer',
            'serviceType',
            'package',
        ]);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load([
            'customer',
            'serviceType',
            'package',
        ]);

        $customers = Customer::orderBy('name')->get();

        $serviceTypes = ServiceType::with([
            'packages' => function ($query) {
                $query->where('status', 'active')
                    ->latest();
            }
        ])
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return view('invoices.edit', compact(
            'invoice',
            'customers',
            'serviceTypes'
        ));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'customer_id' => [
                'required',
                'exists:customers,id',
            ],

            'service_type_id' => [
                'required',
                'exists:service_types,id',
            ],

            'package_id' => [
                'required',
                'exists:packages,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'paid_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'status' => [
                'required',
                'in:draft,sent,partial,paid,cancelled',
            ],

            'issued_at' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        $package = \App\Models\Package::where('id', $data['package_id'])
            ->where('service_type_id', $data['service_type_id'])
            ->firstOrFail();

        $quantity = (int) $data['quantity'];

        $unitPrice = (float) ($package->final_price ?? 0);

        if ($unitPrice <= 0) {
            $unitPrice = (float) ($package->original_price ?? 0);
        }

        $subtotal = $unitPrice * $quantity;

        $discount = (float) ($data['discount'] ?? 0);

        if ($discount > $subtotal) {
            return back()
                ->withErrors([
                    'discount' => 'Discount cannot be greater than subtotal.'
                ])
                ->withInput();
        }

        $grandTotal = max(0, $subtotal - $discount);

        $paidAmount = (float) ($data['paid_amount'] ?? 0);

        if ($paidAmount > $grandTotal) {
            return back()
                ->withErrors([
                    'paid_amount' => 'Paid amount cannot be greater than grand total.'
                ])
                ->withInput();
        }

        $status = $data['status'];

        if ($paidAmount >= $grandTotal && $grandTotal > 0) {
            $status = 'paid';
        } elseif ($paidAmount > 0) {
            $status = 'partial';
        }

        $invoice->update([
            'customer_id' => $data['customer_id'],
            'service_type_id' => $data['service_type_id'],
            'package_id' => $data['package_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'paid_amount' => $paidAmount,
            'currency' => $data['currency'],
            'status' => $status,
            'issued_at' => $data['issued_at'] ?? $invoice->issued_at,
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoiceNumber = $invoice->invoice_number;

        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', "{$invoiceNumber} deleted successfully.");
    }

    public function print(Invoice $invoice)
    {
        $invoice->load([
            'customer',
            'serviceType',
            'package',
        ]);

        return view('invoices.print', compact('invoice'));
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';

        $lastInvoice = Invoice::where(
            'invoice_number',
            'like',
            $prefix . '%'
        )
            ->latest('id')
            ->first();

        $number = 1;

        if ($lastInvoice) {
            $lastNumber = (int) substr(
                $lastInvoice->invoice_number,
                strlen($prefix)
            );

            $number = $lastNumber + 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}