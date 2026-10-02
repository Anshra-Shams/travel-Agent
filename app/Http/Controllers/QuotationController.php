<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Customer;
use App\Models\CustomerService;
use App\Models\Package;
use App\Models\Quotation;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class QuotationController extends Controller
{
    /**
     * Display quotations / bookings.
     */
    public function index(Request $request)
    {
        $query = Quotation::with([
            'customer',
            'agent',
            'account',
            'items',
        ]);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where(
                'payment_status',
                $request->payment_status
            );
        }

        if ($request->filled('customer_id')) {
            $query->where(
                'customer_id',
                $request->customer_id
            );
        }

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'quotation_number',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'reference',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'destination',
                    'like',
                    "%{$search}%"
                )
                ->orWhereHas(
                    'customer',
                    function ($customerQuery) use ($search) {
                        $customerQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'phone',
                                'like',
                                "%{$search}%"
                            );
                    }
                );
            });
        }

        $quotations = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $customers = Customer::orderBy('name')->get();

        $statuses = array_values(
            Quotation::STATUSES
        );

        $filters = [
            'search' => $request->input('search', ''),
            'type' => $request->input('type', ''),
            'status' => $request->input('status', ''),
            'payment_status' => $request->input('payment_status', ''),
            'customer_id' => $request->input('customer_id', ''),
        ];

        return view(
            'quotations.index',
            compact(
                'quotations',
                'customers',
                'statuses',
                'filters'
            )
        );
    }

    /**
     * Create quotation / booking form.
     */
    public function create(Request $request)
    {
        $type = $request->get(
            'type',
            'booking'
        );

        if (
            !in_array(
                $type,
                array_keys(Quotation::TYPES),
                true
            )
        ) {
            $type = 'booking';
        }

        $quotation = null;

        $customers = Customer::orderBy('name')->get();

        $customerServices = CustomerService::with('customer')
            ->latest()
            ->get();

        $accounts = Account::orderBy('name')->get();

        $packages = Package::where(
                'status',
                'active'
            )
            ->with('serviceType')
            ->latest()
            ->get();

        $serviceTypes = ServiceType::where(
                'status',
                'active'
            )
            ->orderBy('name')
            ->get();

        $serviceAmounts = $serviceTypes
            ->pluck(
                'default_amount',
                'name'
            )
            ->toArray();

        return view(
            'quotations.create',
            compact(
                'type',
                'quotation',
                'customers',
                'customerServices',
                'accounts',
                'packages',
                'serviceTypes',
                'serviceAmounts'
            )
        );
    }

    /**
     * Store quotation / booking.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($data) {

            $quotationData = [
                'type' => $data['type'],

                'customer_id' =>
                    $data['customer_id'],

                'agent_id' =>
                    $data['agent_id'] ?? null,

                'account_id' =>
                    $data['account_id'] ?? null,

                'customer_service_id' =>
                    $data['customer_service_id'] ?? null,

                'package_id' =>
                    $data['package_id'] ?? null,

                'service_type' =>
                    $data['service_type']
                    ?? ($data['service'] ?? null),

                'services' =>
                    $data['services']
                    ?? (
                        !empty($data['service'])
                            ? [$data['service']]
                            : []
                    ),

                'destination' =>
                    $data['destination'] ?? null,

                'quotation_date' =>
                    $data['quotation_date']
                    ?? now()->toDateString(),

                'valid_until' =>
                    $data['valid_until'] ?? null,

                'travel_date' =>
                    $data['travel_date'] ?? null,

                'status' =>
                    $data['status']
                    ?? (
                        $data['type'] === 'booking'
                            ? 'Pending'
                            : 'Draft'
                    ),

                'payment_status' =>
                    $data['payment_status']
                    ?? 'Pending',

                'reference' =>
                    $data['reference'] ?? null,

                'discount' =>
                    $data['discount'] ?? 0,

                'tax' =>
                    $data['tax'] ?? 0,

                'payment_terms' =>
                    $data['payment_terms'] ?? null,

                'deposit_required' => 0,

                'payment_due_date' =>
                    $data['payment_due_date'] ?? null,

                'notes' =>
                    $data['notes'] ?? null,

                'terms_conditions' =>
                    $data['terms_conditions'] ?? null,
            ];

            $quotation = Quotation::create(
                $quotationData
            );

            $items = $data['items'] ?? [];

            if (empty($items)) {

                $serviceName =
                    $data['service']
                    ?? $data['service_type']
                    ?? null;

                $package = null;

                if (!empty($data['package_id'])) {
                    $package = Package::find(
                        $data['package_id']
                    );
                }

                if ($package) {

                    $quantity = (float) (
                        $data['members'] ?? 1
                    );

                    $rate = (float) (
                        $package->final_price ?? 0
                    );

                    $items[] = [
                        'service' =>
                            $serviceName
                            ?? $package->name,

                        'description' =>
                            $package->name,

                        'quantity' =>
                            $quantity,

                        'rate' =>
                            $rate,

                        'total' =>
                            $quantity * $rate,
                    ];

                } elseif ($serviceName) {

                    $quantity = (float) (
                        $data['members'] ?? 1
                    );

                    $serviceAmount = (float) (
                        ServiceType::where(
                            'name',
                            $serviceName
                        )->value(
                            'default_amount'
                        ) ?? 0
                    );

                    $items[] = [
                        'service' =>
                            $serviceName,

                        'description' =>
                            $serviceName,

                        'quantity' =>
                            $quantity,

                        'rate' =>
                            $serviceAmount,

                        'total' =>
                            $quantity * $serviceAmount,
                    ];
                }
            }

            $this->syncItems(
                $quotation,
                $items
            );

            $quotation->load('items');

            $quotation->recalculate();

            $quotation->save();

            if ($quotation->type === 'booking') {
                $this->recordPayment(
                    $quotation,
                    $data
                );
            }
        });

        return redirect()
            ->route('quotations.index')
            ->with(
                'success',
                'Quotation created successfully.'
            );
    }

    /**
     * Show quotation.
     */
    public function show(Quotation $quotation)
    {
        $quotation->load([
            'customer',
            'agent',
            'account',
            'serviceRequirement',
            'package',
            'items',
            'activities',
        ]);

        return view(
            'quotations.show',
            compact('quotation')
        );
    }

    /**
     * Edit quotation / booking.
     */
    public function edit(Quotation $quotation)
    {
        $quotation->load([
            'customer',
            'agent',
            'account',
            'serviceRequirement',
            'package',
            'items',
        ]);

        $customers = Customer::orderBy('name')->get();

        $customerServices = CustomerService::with('customer')
            ->latest()
            ->get();

        $accounts = Account::orderBy('name')->get();

        $packages = Package::where(
                'status',
                'active'
            )
            ->with('serviceType')
            ->latest()
            ->get();

        if (
            $quotation->package_id &&
            !$packages->contains(
                'id',
                $quotation->package_id
            )
        ) {
            $currentPackage = Package::with(
                'serviceType'
            )->find(
                $quotation->package_id
            );

            if ($currentPackage) {
                $packages->prepend(
                    $currentPackage
                );
            }
        }

        $serviceTypes = ServiceType::where(
                'status',
                'active'
            )
            ->orderBy('name')
            ->get();

        $serviceAmounts = $serviceTypes
            ->pluck(
                'default_amount',
                'name'
            )
            ->toArray();

        return view(
            'quotations.edit',
            compact(
                'quotation',
                'customers',
                'customerServices',
                'accounts',
                'packages',
                'serviceTypes',
                'serviceAmounts'
            )
        );
    }

    /**
     * Update quotation / booking.
     */
    public function update(
        Request $request,
        Quotation $quotation
    ) {
        $data = $this->validated(
            $request,
            $quotation
        );

        DB::transaction(
            function () use (
                $data,
                $quotation
            ) {

                $quotation->update([

                    'type' =>
                        $data['type'],

                    'customer_id' =>
                        $data['customer_id'],

                    'agent_id' =>
                        $data['agent_id']
                        ?? $quotation->agent_id,

                    'account_id' =>
                        $data['account_id']
                        ?? null,

                    'customer_service_id' =>
                        $data['customer_service_id']
                        ?? null,

                    'package_id' =>
                        $data['package_id']
                        ?? null,

                    'service_type' =>
                        $data['service_type']
                        ?? (
                            $data['service']
                            ?? null
                        ),

                    'services' =>
                        $data['services']
                        ?? (
                            !empty($data['service'])
                                ? [$data['service']]
                                : []
                        ),

                    'destination' =>
                        $data['destination']
                        ?? null,

                    'quotation_date' =>
                        $data['quotation_date']
                        ?? $quotation->quotation_date
                        ?? now()->toDateString(),

                    'valid_until' =>
                        $data['valid_until']
                        ?? null,

                    'travel_date' =>
                        $data['travel_date']
                        ?? null,

                    'status' =>
                        $data['status']
                        ?? $quotation->status,

                    'payment_status' =>
                        $data['payment_status']
                        ?? $quotation->payment_status,

                    'reference' =>
                        $data['reference']
                        ?? null,

                    'discount' =>
                        $data['discount']
                        ?? 0,

                    'tax' =>
                        $data['tax']
                        ?? 0,

                    'payment_terms' =>
                        $data['payment_terms']
                        ?? null,

                    'payment_due_date' =>
                        $data['payment_due_date']
                        ?? null,

                    'notes' =>
                        $data['notes']
                        ?? null,

                    'terms_conditions' =>
                        $data['terms_conditions']
                        ?? null,
                ]);

                $items = $data['items'] ?? [];

                if (empty($items)) {

                    $serviceName =
                        $data['service']
                        ?? $data['service_type']
                        ?? null;

                    $package = null;

                    if (!empty($data['package_id'])) {
                        $package = Package::find(
                            $data['package_id']
                        );
                    }

                    if ($package) {

                        $quantity = (float) (
                            $data['members'] ?? 1
                        );

                        $rate = (float) (
                            $package->final_price ?? 0
                        );

                        $items[] = [
                            'service' =>
                                $serviceName
                                ?? $package->name,

                            'description' =>
                                $package->name,

                            'quantity' =>
                                $quantity,

                            'rate' =>
                                $rate,

                            'total' =>
                                $quantity * $rate,
                        ];

                    } elseif ($serviceName) {

                        $quantity = (float) (
                            $data['members'] ?? 1
                        );

                        $serviceAmount = (float) (
                            ServiceType::where(
                                'name',
                                $serviceName
                            )->value(
                                'default_amount'
                            ) ?? 0
                        );

                        $items[] = [
                            'service' =>
                                $serviceName,

                            'description' =>
                                $serviceName,

                            'quantity' =>
                                $quantity,

                            'rate' =>
                                $serviceAmount,

                            'total' =>
                                $quantity * $serviceAmount,
                        ];
                    }
                }

                $this->syncItems(
                    $quotation,
                    $items
                );

                $quotation->load('items');

                $quotation->recalculate();

                $quotation->save();

                if ($quotation->type === 'booking') {

                    $this->recordPayment(
                        $quotation,
                        $data
                    );

                } else {

                    $this->removePaymentTransaction(
                        $quotation
                    );
                }
            }
        );

        return redirect()
            ->route('quotations.index')
            ->with(
                'success',
                'Quotation updated successfully.'
            );
    }

    /**
     * Delete quotation.
     */
    public function destroy(
        Quotation $quotation
    ) {
        DB::transaction(
            function () use ($quotation) {

                $this->removePaymentTransaction(
                    $quotation
                );

                $quotation->items()->delete();

                $quotation->activities()->delete();

                $quotation->delete();
            }
        );

        return redirect()
            ->route('quotations.index')
            ->with(
                'success',
                'Quotation deleted successfully.'
            );
    }

    /**
     * Convert quotation to booking.
     */
    public function convertToBooking(
        Quotation $quotation
    ) {
        if ($quotation->type === 'booking') {

            return redirect()
                ->back()
                ->with(
                    'info',
                    'This quotation is already a booking.'
                );
        }

        DB::transaction(
            function () use ($quotation) {

                $quotation->update([
                    'type' => 'booking',
                    'status' => 'Confirmed',
                    'payment_status' => 'Pending',
                ]);

                $quotation->load('items');

                $quotation->recalculate();

                $quotation->save();
            }
        );

        return redirect()
            ->route(
                'quotations.edit',
                $quotation
            )
            ->with(
                'success',
                'Quotation converted to booking successfully.'
            );
    }

    /**
     * Requirement API.
     */
    public function requirement(
        Request $request
    ) {
        $request->validate([

            'customer_id' => [
                'required',
                'exists:customers,id',
            ],

            'service' => [
                'required',
                'string',
            ],
        ]);

        $customerService = CustomerService::query()
            ->where(
                'customer_id',
                $request->customer_id
            )
            ->where(
                function ($query) use ($request) {

                    $query
                        ->where(
                            'service_type',
                            $request->service
                        )
                        ->orWhere(
                            'service',
                            $request->service
                        );
                }
            )
            ->latest()
            ->first();

        if (!$customerService) {

            return response()->json([
                'requirement' => null,
                'all_services' => [],
            ]);
        }

        $travelDate = null;

        if (!empty($customerService->travel_date)) {

            try {

                $travelDate = \Carbon\Carbon::parse(
                    $customerService->travel_date
                )->format('Y-m-d');

            } catch (\Throwable $e) {

                $travelDate = null;
            }
        }

        $requirement = [

            'id' =>
                $customerService->id,

            'service_type' =>
                $customerService->service_type
                ?? $customerService->service
                ?? $request->service,

            'status' =>
                $customerService->status
                ?? 'Pending',

            'destination' =>
                $customerService->destination
                ?? null,

            'travel_date' =>
                $travelDate,

            'travelers' =>
                (int) (
                    $customerService->travelers
                    ?? 1
                ),

            'requirements' =>
                $customerService->requirements
                ?? null,

            'general' => [

                [
                    'label' => 'Service',
                    'value' =>
                        $customerService->service_type
                        ?? $customerService->service
                        ?? $request->service,
                ],

                [
                    'label' => 'Destination',
                    'value' =>
                        $customerService->destination
                        ?? '—',
                ],

                [
                    'label' => 'Travel Date',
                    'value' =>
                        $travelDate
                        ?? '—',
                ],

                [
                    'label' => 'Travelers',
                    'value' =>
                        $customerService->travelers
                        ?? 1,
                ],

                [
                    'label' => 'Status',
                    'value' =>
                        $customerService->status
                        ?? 'Pending',
                ],
            ],

            'specific' => [],
        ];

        $allServices = CustomerService::where(
            'customer_id',
            $request->customer_id
        )
            ->latest()
            ->get()
            ->map(
                function ($service) {

                    return [
                        'id' =>
                            $service->id,

                        'service_type' =>
                            $service->service_type
                            ?? $service->service,

                        'status' =>
                            $service->status,
                    ];
                }
            )
            ->values();

        return response()->json([
            'requirement' =>
                $requirement,

            'all_services' =>
                $allServices,
        ]);
    }

    /**
     * Validate request.
     */
    protected function validated(
        Request $request,
        ?Quotation $quotation = null
    ): array {

        $statuses = Quotation::STATUSES;

        $paymentStatuses =
            Quotation::PAYMENT_STATUSES;

        $data = $request->validate([

            'type' => [
                'required',
                Rule::in(
                    array_keys(
                        Quotation::TYPES
                    )
                ),
            ],

            'customer_id' => [
                'required',
                'exists:customers,id',
            ],

            'agent_id' => [
                'nullable',
                'exists:users,id',
            ],

            'account_id' => [
                'nullable',
                'exists:accounts,id',
            ],

            'customer_service_id' => [
                'nullable',
                'exists:customer_services,id',
            ],

            'package_id' => [
                'nullable',
                'exists:packages,id',
            ],

            'service' => [
                'nullable',
                'string',
                'max:255',
            ],

            'service_type' => [
                'nullable',
                'string',
                'max:255',
            ],

            'services' => [
                'nullable',
                'array',
            ],

            'services.*' => [
                'nullable',
                'string',
            ],

            'members' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'destination' => [
                'nullable',
                'string',
                'max:255',
            ],

            'travel_date' => [
                'nullable',
                'date',
            ],

            'quotation_date' => [
                'nullable',
                'date',
            ],

            'valid_until' => [
                'nullable',
                'date',
            ],

            'status' => [
                'nullable',
                'string',
                Rule::in($statuses),
            ],

            'payment_status' => [
                'nullable',
                'string',
                Rule::in($paymentStatuses),
            ],

            'reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'subtotal' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'grand_total' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'deposit_required' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'advance' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'payment_due_date' => [
                'nullable',
                'date',
            ],

            'payment_terms' => [
                'nullable',
                'string',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'terms_conditions' => [
                'nullable',
                'string',
            ],

            'items' => [
                'nullable',
                'array',
            ],

            'items.*.id' => [
                'nullable',
                'integer',
            ],

            'items.*.service' => [
                'nullable',
                'string',
                'max:255',
            ],

            'items.*.description' => [
                'nullable',
                'string',
            ],

            'items.*.quantity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.total' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);

        if (
            empty($data['service_type']) &&
            !empty($data['service'])
        ) {
            $data['service_type'] =
                $data['service'];
        }

        if (
            empty($data['services']) &&
            !empty($data['service'])
        ) {
            $data['services'] = [
                $data['service']
            ];
        }

        return $data;
    }

    /**
     * Sync quotation items.
     */
    protected function syncItems(
        Quotation $quotation,
        array $items
    ): void {

        $existingIds = [];

        foreach ($items as $index => $itemData) {

            $quantity = (float) (
                $itemData['quantity'] ?? 0
            );

            $rate = (float) (
                $itemData['rate'] ?? 0
            );

            $total = array_key_exists(
                'total',
                $itemData
            )
                ? (float) $itemData['total']
                : (
                    $quantity * $rate
                );

            $payload = [

                'service' =>
                    $itemData['service']
                    ?? null,

                'description' =>
                    $itemData['description']
                    ?? null,

                'quantity' =>
                    $quantity,

                'rate' =>
                    $rate,

                'total' =>
                    $total,

                'sort_order' =>
                    $index,
            ];

            if (!empty($itemData['id'])) {

                $item = $quotation
                    ->items()
                    ->whereKey(
                        $itemData['id']
                    )
                    ->first();

                if ($item) {

                    $item->update(
                        $payload
                    );

                    $existingIds[] =
                        $item->id;

                    continue;
                }
            }

            $item = $quotation
                ->items()
                ->create($payload);

            $existingIds[] =
                $item->id;
        }

        if (empty($existingIds)) {

            $quotation
                ->items()
                ->delete();

        } else {

            $quotation
                ->items()
                ->whereNotIn(
                    'id',
                    $existingIds
                )
                ->delete();
        }
    }

    /**
     * Record booking payment.
     */
    protected function recordPayment(
        Quotation $quotation,
        array $data
    ): void {

        $accountId =
            $data['account_id']
            ?? null;

        $paidTotal = (float) (
            $data['payment_amount']
            ?? $data['advance']
            ?? $data['deposit_required']
            ?? 0
        );

        $paidTotal =
            max(0, $paidTotal);

        $grandTotal =
            (float) (
                $quotation->grand_total
                ?? 0
            );

        if ($paidTotal > $grandTotal) {
            $paidTotal =
                $grandTotal;
        }

        $this->removePaymentTransaction(
            $quotation
        );

        if (
            $paidTotal > 0 &&
            $accountId
        ) {

            $account =
                Account::find($accountId);

            if ($account) {

                $customerName =
                    optional(
                        $quotation->customer
                    )->name
                    ?? 'Customer';

                AccountTransaction::create([

                    'account_id' =>
                        $account->id,

                    'date' =>
                        now()->toDateString(),

                    'reference' =>
                        $quotation->quotation_number,

                    'description' =>
                        "Payment received from {$customerName} - {$quotation->quotation_number}",

                    'debit' =>
                        0,

                    'credit' =>
                        $paidTotal,
                ]);

                $this->recalculateAccountBalance(
                    $account
                );
            }
        }

        if ($paidTotal <= 0) {

            $paymentStatus = 'Pending';

        } elseif ($paidTotal >= $grandTotal) {

            $paymentStatus = 'Paid';

        } else {

            $paymentStatus = 'Partial';
        }

        $quotation->update([

            'payment_status' =>
                $paymentStatus,

            'deposit_required' =>
                $paidTotal,

            'remaining_amount' =>
                max(
                    0,
                    $grandTotal - $paidTotal
                ),
        ]);
    }

    /**
     * Remove quotation payment transaction.
     */
    protected function removePaymentTransaction(
        Quotation $quotation
    ): void {

        $transaction = AccountTransaction::where(
            'reference',
            $quotation->quotation_number
        )->first();

        if (!$transaction) {
            return;
        }

        $account =
            $transaction->account;

        $transaction->delete();

        if ($account) {

            $this->recalculateAccountBalance(
                $account
            );
        }
    }

    /**
     * Recalculate account balance.
     */
    protected function recalculateAccountBalance(
        Account $account
    ): void {

        $openingBalance =
            (float) (
                $account->opening_balance
                ?? 0
            );

        $credit =
            (float) $account
                ->transactions()
                ->sum('credit');

        $debit =
            (float) $account
                ->transactions()
                ->sum('debit');

        $total =
            $openingBalance
            + $credit
            - $debit;

        $account->update([
            'current_balance' =>
                $total,
        ]);
    }
}