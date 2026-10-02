@php
    $reqForEdit = $quotation?->serviceRequirement;

    $initialRequirement = $reqForEdit ? [
        'id' => $reqForEdit->id,
        'service_type' => $reqForEdit->service_type,
        'status' => $reqForEdit->status,
        'destination' => $reqForEdit->destination,
        'travel_date' => $reqForEdit->travel_date
            ? \Carbon\Carbon::parse($reqForEdit->travel_date)->format('Y-m-d')
            : null,
        'travelers' => $reqForEdit->travelers,
        'requirements' => $reqForEdit->requirements,
    ] : null;
@endphp
<x-admin-layout title="{{ $quotation ? 'Edit ' . $quotation->quotation_number : 'Add Booking' }}">

    <script>
        function validateBookingForm(alpineData) {

            const errors = [];

            if (!alpineData.customerId) {
                errors.push('Please select a customer');
            }

            if (!alpineData.service) {
                errors.push('Please select a service');
            }

            if (!alpineData.selectedRequirement) {
                errors.push(
                    'No saved requirement found. Create one from the Services module first.'
                );
            }

            if (!alpineData.packageId) {
                errors.push('Please select a package');
            }

            if (errors.length) {

                if (typeof Swal !== 'undefined') {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Information',
                        html:
                            '<ul style="text-align:left;margin:0;padding-left:16px;">' +
                            errors.map(function (e) {
                                return '<li>' + e + '</li>';
                            }).join('') +
                            '</ul>',
                        confirmButtonColor: '#0ea5e9',
                    });

                } else {

                    alert(errors.join('\n'));

                }

                return false;
            }

            return true;
        }
    </script>

    <div
        class="mx-auto max-w-7xl"
        x-data="{

            activeType: @js($quotation ? $quotation->type : 'booking'),

            serviceAmounts: @js($serviceAmounts ?? []),

            packages: @js(
                ($packages ?? collect())
                    ->map(function ($p) {
                        return [
                            'id' => $p->id,
                            'service_type' => $p->serviceType?->name,
                            'name' => $p->name,
                            'final_price' => (float) ($p->final_price ?? 0),
                            'currency' => $p->currency ?? 'PKR',
                            'departure_date' => $p->departure_date
                                ? \Carbon\Carbon::parse($p->departure_date)->format('Y-m-d')
                                : null,
                            'return_date' => $p->return_date
                                ? \Carbon\Carbon::parse($p->return_date)->format('Y-m-d')
                                : null,
                            'duration_days' => $p->duration_days,
                        ];
                    })
                    ->values()
            ),

            packageId: @js(
                old('package_id', $quotation?->package_id ?? '')
            ),

            customerTravelers: @js(
                ($customers ?? collect())
                    ->pluck('travelers', 'id')
                    ->map(function ($t) {
                        return (int) $t;
                    })
                    ->toArray()
            ),

            customerId: @js(
                old('customer_id', $quotation?->customer_id ?? '')
            ),

            service: @js(
                $quotation
                    ? ($quotation->services_list[0] ?? '')
                    : old('service', '')
            ),

            members: @js(
                (int) (
                    $quotation?->serviceRequirement?->travelers
                    ?? old('members', 1)
                    ?? 1
                )
            ),

            discount: @js(
                (float) old(
                    'discount',
                    $quotation?->discount ?? 0
                )
            ),

            travelDate: @js(
                old(
                    'travel_date',
                    $quotation?->serviceRequirement?->travel_date
                        ? \Carbon\Carbon::parse(
                            $quotation->serviceRequirement->travel_date
                        )->format('Y-m-d')
                        : ''
                ) ?? ''
            ),

            paymentAmount: @js(
                (float) old(
                    'payment_amount',
                    $quotation?->deposit_required ?? 0
                )
            ),

            advance: @js(
                (float) old(
                    'advance',
                    $quotation?->deposit_required ?? 0
                )
            ),

            account: @js(
                old(
                    'account_id',
                    $quotation?->account_id ?? ''
                )
            ),

            validUntil: @js(
                old(
                    'valid_until',
                    $quotation?->valid_until
                        ? \Carbon\Carbon::parse(
                            $quotation->valid_until
                        )->format('Y-m-d')
                        : ''
                ) ?? ''
            ),

            reference: @js(
                old(
                    'reference',
                    $quotation?->reference ?? ''
                )
            ),

            status: @js(
                old(
                    'status',
                    $quotation?->status ?? 'Pending'
                )
            ),

            totalServiceAmount: 0,

            selectedRequirement: @js($initialRequirement),

            allServices: [],

            loadingRequirement: false,

            init() {

                if (!this.validUntil) {

                    const d = new Date();

                    d.setDate(d.getDate() + 14);

                    this.validUntil =
                        d.toISOString().slice(0, 10);
                }

                this.recalculate();
            },

            get filteredPackages() {

                return this.packages.filter(
                    p => p.service_type === this.service
                );
            },

            get selectedPackage() {

                return this.packages.find(
                    p =>
                        String(p.id) ===
                        String(this.packageId)
                );
            },

            get unitAmount() {

                if (this.selectedPackage) {

                    return Number(
                        this.selectedPackage.final_price || 0
                    );
                }

                return Number(
                    this.serviceAmounts[this.service] || 0
                );
            },

            get finalTotal() {

                return Math.max(
                    0,
                    (Number(this.totalServiceAmount) || 0) -
                    (Number(this.discount) || 0)
                );
            },

            get remaining() {

                return Math.max(
                    0,
                    this.finalTotal -
                    (Number(this.advance) || 0)
                );
            },

            selectPackage() {

                const pkg = this.selectedPackage;

                if (pkg && pkg.departure_date) {

                    this.travelDate =
                        pkg.departure_date;
                }

                this.recalculate();
            },

            changeService() {

                this.packageId = '';
                this.selectedRequirement = null;
                this.travelDate = '';

                if (this.customerId && this.service) {
                    this.fetchRequirement();
                }

                this.recalculate();
            },

            changeCustomer() {

                this.selectedRequirement = null;
                this.packageId = '';
                this.travelDate = '';

                if (
                    this.customerId &&
                    this.service
                ) {
                    this.fetchRequirement();
                }

                this.recalculate();
            },

            setAdvance(v) {

                let val = Math.max(
                    0,
                    Number(v) || 0
                );

                if (val > this.finalTotal) {
                    val = this.finalTotal;
                }

                this.advance = val;
                this.paymentAmount = val;
            },

            fmt(v) {

                return 'Rs. ' +
                    Number(v || 0)
                        .toLocaleString('en-IN', {
                            maximumFractionDigits: 0
                        });
            },

            recalculate() {

                this.totalServiceAmount =
                    this.unitAmount *
                    (Number(this.members) || 1);

                if (
                    Number(this.discount) >
                    this.totalServiceAmount
                ) {
                    this.discount =
                        this.totalServiceAmount;
                }

                if (
                    Number(this.advance) >
                    this.finalTotal
                ) {
                    this.advance =
                        this.finalTotal;
                }

                this.paymentAmount =
                    this.advance;
            },

            async fetchRequirement() {

                if (
                    !this.customerId ||
                    !this.service
                ) {

                    this.selectedRequirement = null;

                    return;
                }

                this.loadingRequirement = true;

                try {

                    const res = await fetch(
                        '/quotations/requirement?customer_id=' +
                        encodeURIComponent(this.customerId) +
                        '&service=' +
                        encodeURIComponent(this.service),
                        {
                            headers: {
                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest'
                            },

                            cache: 'no-store'
                        }
                    );

                    if (!res.ok) {

                        throw new Error(
                            'Failed to load requirement'
                        );
                    }

                    const data =
                        await res.json();

                    this.selectedRequirement =
                        data.requirement || null;

                    this.allServices =
                        data.all_services || [];

                    if (data.requirement) {

                        this.members =
                            Number(
                                data.requirement.travelers ||
                                this.customerTravelers[
                                    this.customerId
                                ] ||
                                1
                            );

                        this.travelDate =
                            data.requirement.travel_date ||
                            '';

                    } else {

                        this.members =
                            Number(
                                this.customerTravelers[
                                    this.customerId
                                ] || 1
                            );
                    }

                    this.recalculate();

                } catch (e) {

                    console.error(
                        'Requirement error:',
                        e
                    );

                    this.selectedRequirement = null;

                } finally {

                    this.loadingRequirement = false;
                }
            }
        }"
    >

        <!-- PAGE HEADER -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h1 class="text-2xl font-bold text-gray-900">

                    <span x-show="activeType === 'booking'">

                        {{ $quotation ? 'Edit Booking' : 'Create New Booking' }}

                    </span>

                    <span
                        x-show="activeType === 'quotation'"
                        x-cloak
                    >

                        {{ $quotation ? 'Edit Quotation' : 'Create New Quotation' }}

                    </span>

                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Customer ki saved requirement se booking/quotation banayein.
                </p>

            </div>

            <div class="flex items-center gap-3">

                <div class="inline-flex rounded-xl border border-gray-200 bg-gray-100 p-1">

                    <button
                        type="button"
                        @click="activeType = 'booking'; status = 'Pending'"
                        :class="
                            activeType === 'booking'
                                ? 'bg-white text-sky-700 shadow-sm ring-1 ring-gray-200'
                                : 'text-gray-500 hover:text-gray-700'
                        "
                        class="inline-flex items-center gap-1.5 rounded-lg px-5 py-1.5 text-sm font-semibold transition"
                    >
                        Booking
                    </button>

                    <button
                        type="button"
                        @click="activeType = 'quotation'; status = 'Draft'"
                        :class="
                            activeType === 'quotation'
                                ? 'bg-white text-sky-700 shadow-sm ring-1 ring-gray-200'
                                : 'text-gray-500 hover:text-gray-700'
                        "
                        class="inline-flex items-center gap-1.5 rounded-lg px-5 py-1.5 text-sm font-semibold transition"
                    >
                        Quotation
                    </button>

                </div>

                <a
                    href="{{ route('quotations.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                >
                    ← Back
                </a>

            </div>

        </div>

        <!-- MAIN FORM -->
        <form
            method="POST"
            action="{{ $quotation
                ? route('quotations.update', $quotation)
                : route('quotations.store') }}"
            @submit.prevent="
                if (
                    validateBookingForm({
                        customerId: customerId,
                        service: service,
                        selectedRequirement: selectedRequirement,
                        packageId: packageId
                    })
                ) {
                    $el.submit();
                }
            "
        >

            @csrf

            @if ($quotation)
                @method('PUT')
            @endif

            <input
                type="hidden"
                name="type"
                :value="activeType"
            >

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <!-- LEFT COLUMN -->
                <div class="space-y-6 lg:col-span-2">

                    <!-- CUSTOMER & SERVICE -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <h3 class="text-base font-semibold text-gray-900">
                            Customer &amp; Service
                        </h3>

                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">

                            <!-- CUSTOMER -->
                            <div>

                                <x-input-label
                                    for="customer_id"
                                    value="Select Customer *"
                                />

                                <select
                                    id="customer_id"
                                    name="customer_id"
                                    x-model="customerId"
                                    @change="changeCustomer()"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                    required
                                >

                                    <option value="">
                                        Select Customer
                                    </option>

                                    @foreach (($customers ?? collect()) as $customer)

                                        <option value="{{ $customer->id }}">
                                            {{ $customer->name }}
                                            ({{ $customer->phone ?: 'no phone' }})
                                        </option>

                                    @endforeach

                                </select>

                                <x-input-error
                                    :messages="$errors->get('customer_id')"
                                    class="mt-1"
                                />

                            </div>

                            <!-- SERVICE -->
                            <div>

                                <x-input-label
                                    for="service"
                                    value="Select Service *"
                                />

                                <select
                                    id="service"
                                    name="service"
                                    x-model="service"
                                    @change="changeService()"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                    required
                                >

                                    <option value="">
                                        Select Service
                                    </option>

                                    @foreach (($serviceTypes ?? collect()) as $serviceType)

                                        <option value="{{ $serviceType->name }}">
                                            {{ $serviceType->name }}
                                        </option>

                                    @endforeach

                                </select>

                                <x-input-error
                                    :messages="$errors->get('service')"
                                    class="mt-1"
                                />

                            </div>

                        </div>

                    </div>

                    <!-- SAVED REQUIREMENT -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <div>

                                <h3 class="text-base font-semibold text-gray-900">
                                    Saved Service Requirement
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Customer ki saved requirement yahan show hogi.
                                </p>

                            </div>

                            <span
                                x-show="loadingRequirement"
                                x-cloak
                                class="text-sm text-sky-600"
                            >
                                Loading...
                            </span>

                        </div>

                        <!-- NO REQUIREMENT -->
                        <div
                            x-show="!loadingRequirement && !selectedRequirement"
                            x-cloak
                            class="mt-4 rounded-lg border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500"
                        >

                            <span x-show="customerId && service">
                                Create a requirement first from the
                            </span>

                            <span x-show="!customerId || !service">
                                Please select a customer and service first.
                            </span>

                            <a
                                x-show="customerId && service"
                                href="{{ route('services.create') }}"
                                class="font-semibold text-sky-600 hover:underline"
                            >
                                Services module
                            </a>

                        </div>

                        <!-- REQUIREMENT DETAILS -->
                        <div
                            x-show="!loadingRequirement && selectedRequirement"
                            x-cloak
                            class="mt-4"
                        >

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Service
                                    </p>

                                    <p
                                        class="mt-1 font-medium text-gray-900"
                                        x-text="selectedRequirement?.service_type || '-'"
                                    ></p>

                                </div>

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Status
                                    </p>

                                    <p
                                        class="mt-1 font-medium text-gray-900"
                                        x-text="selectedRequirement?.status || '-'"
                                    ></p>

                                </div>

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Destination
                                    </p>

                                    <p
                                        class="mt-1 font-medium text-gray-900"
                                        x-text="selectedRequirement?.destination || '-'"
                                    ></p>

                                </div>

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Travelers
                                    </p>

                                    <p
                                        class="mt-1 font-medium text-gray-900"
                                        x-text="selectedRequirement?.travelers || members || 1"
                                    ></p>

                                </div>

                            </div>

                            <div class="mt-4">

                                <p class="text-xs text-gray-500">
                                    Requirements
                                </p>

                                <p
                                    class="mt-1 whitespace-pre-line text-sm text-gray-700"
                                    x-text="selectedRequirement?.requirements || '-'"
                                ></p>

                            </div>

                        </div>

                    </div>

                    <!-- PACKAGE SELECTION -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <div class="flex items-center justify-between">

                            <div>

                                <h3 class="text-base font-semibold text-gray-900">
                                    Package Selection
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Selected service ka package choose karein.
                                </p>

                            </div>

                            <span
                                x-show="selectedPackage"
                                x-cloak
                                class="rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700"
                            >
                                Package Selected
                            </span>

                        </div>

                        <div class="mt-4">

                            <label
                                for="package_id"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Package <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="package_id"
                                name="package_id"
                                x-model="packageId"
                                @change="selectPackage()"
                                class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                required
                            >

                                <option value="">
                                    Select Package
                                </option>

                                <template
                                    x-for="pkg in filteredPackages"
                                    :key="pkg.id"
                                >

                                    <option
                                        :value="pkg.id"
                                        x-text="pkg.name + ' — ' + fmt(pkg.final_price) + ' / person'"
                                    ></option>

                                </template>

                            </select>

                            <p
                                x-show="service && filteredPackages.length === 0"
                                x-cloak
                                class="mt-2 text-sm text-amber-600"
                            >
                                No active package found for this service.
                            </p>

                        </div>

                        <!-- SELECTED PACKAGE DETAILS -->
                        <div
                            x-show="selectedPackage"
                            x-cloak
                            class="mt-4 rounded-lg border border-indigo-100 bg-indigo-50 p-4"
                        >

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Package
                                    </p>

                                    <p
                                        class="mt-1 font-semibold text-gray-900"
                                        x-text="selectedPackage?.name || '-'"
                                    ></p>

                                </div>

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Price / Person
                                    </p>

                                    <p
                                        class="mt-1 font-semibold text-gray-900"
                                        x-text="selectedPackage ? fmt(selectedPackage.final_price) : '-'"
                                    ></p>

                                </div>

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Duration
                                    </p>

                                    <p
                                        class="mt-1 font-semibold text-gray-900"
                                        x-text="selectedPackage?.duration_days ? selectedPackage.duration_days + ' Days' : '-'"
                                    ></p>

                                </div>

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Departure
                                    </p>

                                    <p
                                        class="mt-1 font-semibold text-gray-900"
                                        x-text="selectedPackage?.departure_date || '-'"
                                    ></p>

                                </div>

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Return
                                    </p>

                                    <p
                                        class="mt-1 font-semibold text-gray-900"
                                        x-text="selectedPackage?.return_date || '-'"
                                    ></p>

                                </div>

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Currency
                                    </p>

                                    <p
                                        class="mt-1 font-semibold text-gray-900"
                                        x-text="selectedPackage?.currency || 'PKR'"
                                    ></p>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- TRAVEL INFORMATION -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <h3 class="text-base font-semibold text-gray-900">
                            Travel Information
                        </h3>

                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">

                            <div>

                                <x-input-label
                                    for="members"
                                    value="Number of Travelers *"
                                />

                                <input
                                    id="members"
                                    type="number"
                                    name="members"
                                    min="1"
                                    x-model.number="members"
                                    @input="recalculate()"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                    required
                                >

                            </div>

                            <div>

                                <x-input-label
                                    for="travel_date"
                                    value="Travel Date"
                                />

                                <input
                                    id="travel_date"
                                    type="date"
                                    name="travel_date"
                                    x-model="travelDate"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                >

                            </div>

                            <div>

                                <label class="block text-sm font-medium text-gray-700">
                                    Destination
                                </label>

                                <input
                                    type="text"
                                    name="destination"
                                    :value="selectedRequirement?.destination || ''"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                    placeholder="Destination"
                                >

                            </div>

                        </div>

                    </div>

                    <!-- NOTES & TERMS -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <h3 class="text-base font-semibold text-gray-900">
                            Notes &amp; Terms
                        </h3>

                        <div class="mt-4 space-y-4">

                            <div>

                                <x-input-label
                                    for="notes"
                                    value="Notes"
                                />

                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows="4"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                    placeholder="Additional notes..."
                                >{{ old('notes', $quotation?->notes ?? '') }}</textarea>

                            </div>

                            <div>

                                <x-input-label
                                    for="terms_conditions"
                                    value="Terms & Conditions"
                                />

                                <textarea
                                    id="terms_conditions"
                                    name="terms_conditions"
                                    rows="4"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                    placeholder="Terms and conditions..."
                                >{{ old('terms_conditions', $quotation?->terms_conditions ?? '') }}</textarea>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- RIGHT COLUMN -->
                <div class="space-y-6">

                    <!-- PAYMENT SUMMARY -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <h3 class="text-base font-semibold text-gray-900">
                            Payment Summary
                        </h3>

                        <div class="mt-5 space-y-4">

                            <div class="flex items-center justify-between text-sm">

                                <span class="text-gray-500">
                                    Travelers
                                </span>

                                <span
                                    class="font-semibold text-gray-900"
                                    x-text="members || 1"
                                ></span>

                            </div>

                            <div class="flex items-center justify-between text-sm">

                                <span class="text-gray-500">
                                    Package Price / Person
                                </span>

                                <span
                                    class="font-semibold text-gray-900"
                                    x-text="fmt(unitAmount)"
                                ></span>

                            </div>

                            <div class="flex items-center justify-between text-sm">

                                <span class="text-gray-500">
                                    Subtotal
                                </span>

                                <span
                                    class="font-semibold text-gray-900"
                                    x-text="fmt(totalServiceAmount)"
                                ></span>

                            </div>

                            <div>

                                <label
                                    for="discount"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Discount
                                </label>

                                <input
                                    id="discount"
                                    type="number"
                                    name="discount"
                                    min="0"
                                    step="0.01"
                                    x-model.number="discount"
                                    @input="recalculate()"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                >

                            </div>

                            <div class="rounded-lg bg-sky-50 p-4">

                                <div class="flex items-center justify-between">

                                    <span class="text-sm font-medium text-sky-700">
                                        Grand Total
                                    </span>

                                    <span
                                        class="text-xl font-bold text-sky-800"
                                        x-text="fmt(finalTotal)"
                                    ></span>

                                </div>

                            </div>

                            <div>

                                <label
                                    for="advance"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Advance Payment
                                </label>

                                <input
                                    id="advance"
                                    type="number"
                                    name="advance"
                                    min="0"
                                    step="0.01"
                                    x-model.number="advance"
                                    @input="setAdvance($event.target.value)"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                >

                            </div>

                            <div>

                                <x-input-label
                                    for="account_id"
                                    value="Payment Account"
                                />

                                <select
                                    id="account_id"
                                    name="account_id"
                                    x-model="account"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                >

                                    <option value="">
                                        Select Account
                                    </option>

                                    @foreach (($accounts ?? collect()) as $acc)

                                        <option value="{{ $acc->id }}">
                                            {{ $acc->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="flex items-center justify-between text-sm">

                                <span class="text-gray-500">
                                    Remaining
                                </span>

                                <span
                                    class="font-semibold text-gray-900"
                                    x-text="fmt(remaining)"
                                ></span>

                            </div>

                        </div>

                    </div>

                    <!-- BOOKING INFORMATION -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">

                        <h3 class="text-base font-semibold text-gray-900">
                            Booking Information
                        </h3>

                        <div class="mt-4 space-y-4">

                            <div x-show="activeType === 'booking'">

                                <x-input-label
                                    for="status"
                                    value="Booking Status"
                                />

                                <select
                                    id="status"
                                    name="status"
                                    x-model="status"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                    required
                                >

                                    @foreach (\App\Models\Quotation::BOOKING_STATUSES as $bookingStatus)

                                        <option value="{{ $bookingStatus }}">
                                            {{ $bookingStatus }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div
                                x-show="activeType === 'quotation'"
                                x-cloak
                            >

                                <x-input-label
                                    for="quotation_status"
                                    value="Quotation Status"
                                />

                                <select
                                    id="quotation_status"
                                    name="status"
                                    x-model="status"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                >

                                    @foreach (\App\Models\Quotation::STATUSES as $quotationStatus)

                                        <option value="{{ $quotationStatus }}">
                                            {{ $quotationStatus }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div
                                x-show="activeType === 'quotation'"
                                x-cloak
                            >

                                <x-input-label
                                    for="valid_until"
                                    value="Valid Until"
                                />

                                <input
                                    id="valid_until"
                                    type="date"
                                    name="valid_until"
                                    x-model="validUntil"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500"
                                >

                            </div>

                            <!-- Remaining Amount -->
                            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm">

                                <span class="font-medium text-gray-600">
                                    Remaining Amount (Auto)
                                </span>

                                <span
                                    class="font-semibold text-sky-700"
                                    x-text="fmt(remaining)"
                                ></span>

                            </div>

                        </div>

                    </div>
                </div>

            </div>

            <!-- HIDDEN VALUES -->
            <input
                type="hidden"
                name="subtotal"
                :value="totalServiceAmount"
            >

            <input
                type="hidden"
                name="grand_total"
                :value="finalTotal"
            >

            <input
                type="hidden"
                name="payment_amount"
            >

            <!-- FORM BUTTONS -->
            <div class="mt-6 flex items-center justify-end gap-3">

                <a
                    href="{{ route('quotations.index') }}"
                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    :disabled="loadingRequirement"
                    class="inline-flex items-center rounded-lg bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700 disabled:cursor-not-allowed disabled:opacity-50"
                >

                    {{ $quotation ? 'Update' : 'Create' }}

                    <span
                        x-text="
                            activeType === 'booking'
                                ? ' Booking'
                                : ' Quotation'
                        "
                    ></span>

                </button>

            </div>

        </form>

    </div></x-admin-layout>