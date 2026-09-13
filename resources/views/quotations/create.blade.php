@php
    $reqForEdit = $quotation?->serviceRequirement;
    $initialRequirement = $reqForEdit ? [
        'id' => $reqForEdit->id,
        'service_type' => $reqForEdit->service_type,
        'status' => $reqForEdit->status,
        'destination' => $reqForEdit->destination,
        'travel_date' => $reqForEdit->travel_date?->format('Y-m-d'),
        'travelers' => $reqForEdit->travelers,
        'requirements' => $reqForEdit->requirements,
    ] : null;
@endphp
<x-admin-layout title="{{ $quotation ? 'Edit ' . $quotation->quotation_number : 'Add Booking' }}">
    <script>
        function validateBookingForm(alpineData) {
            const errors = [];
            if (!alpineData.customerId) errors.push('Please select a customer');
            if (!alpineData.service) errors.push('Please select a service');
            if (!alpineData.selectedRequirement) errors.push('No saved requirement found. Create one from the Services module first.');

            if (errors.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    html: '<ul style="text-align:left;margin:0;padding-left:16px;">' + errors.map(function(e){ return '<li>' + e + '</li>'; }).join('') + '</ul>',
                    confirmButtonColor: '#0ea5e9',
                });
                return false;
            }
            return true;
        }
    </script>
    <div class="mx-auto max-w-7xl" x-data="{
        activeType: @js($quotation ? $quotation->type : 'booking'),
        serviceAmounts: @js($serviceAmounts),
        customerTravelers: @js($customers->pluck('travelers', 'id')->map(fn ($t) => (int) $t)->toArray()),
        customerId: @js(old('customer_id', $quotation?->customer_id ?? '')),
        service: @js($quotation ? ($quotation->services_list[0] ?? '') : old('service', '')),
        members: @js((int) ($quotation?->serviceRequirement?->travelers ?? old('members', 0))),
        discount: @js((float) old('discount', $quotation?->discount ?? 0)),
        travelDate: @js(old('travel_date', $quotation?->serviceRequirement?->travel_date?->format('Y-m-d')) ?? ''),
        paymentAmount: @js((float) old('payment_amount', 0)),
        advance: @js((float) old('advance', 0)),
        account: '',
        validUntil: @js(old('valid_until', $quotation?->valid_until?->format('Y-m-d'))),
        reference: @js(old('reference', $quotation?->reference)),
        totalServiceAmount: 0,
        selectedRequirement: @js($initialRequirement ?? old('req_hint')),
        allServices: [],
        loadingRequirement: false,
        init() {
            if (!this.validUntil) {
                const d = new Date();
                d.setDate(d.getDate() + 14);
                this.validUntil = d.toISOString().slice(0, 10);
            }
            this.recalculate();
        },
        get unitAmount() {
            return Number(this.serviceAmounts[this.service] || 0);
        },
        get finalTotal() {
            return Math.max(0, (Number(this.totalServiceAmount) || 0) - (Number(this.discount) || 0));
        },
        get remaining() {
            return Math.max(0, this.finalTotal - this.advance);
        },
        setPaymentAmount(v) {
            let val = Math.max(0, Number(v) || 0);
            if (val > this.finalTotal) { val = this.finalTotal; }
            this.paymentAmount = val;
            this.advance = val;
        },
        setAdvance(v) {
            let val = Math.max(0, Number(v) || 0);
            if (val > this.finalTotal) { val = this.finalTotal; }
            this.advance = val;
            this.paymentAmount = val;
        },
        fmt(v) {
            return 'Rs. ' + Number(v || 0).toLocaleString('en-IN', { maximumFractionDigits: 0 });
        },
        recalculate() {
            this.totalServiceAmount = this.unitAmount * (Number(this.members) || 1);
        },
        async fetchRequirement() {
            if (!this.customerId || !this.service) { this.selectedRequirement = null; return; }
            this.loadingRequirement = true;
            try {
                const res = await fetch('/quotations/requirement?customer_id=' + this.customerId + '&service=' + encodeURIComponent(this.service), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    cache: 'no-store'
                });
                const data = await res.json();
                this.selectedRequirement = data.requirement;
                this.allServices = data.all_services || [];
                if (data.requirement) {
                    this.members = data.requirement.travelers || this.customerTravelers[this.customerId] || 1;
                    this.travelDate = data.requirement.travel_date || '';
                }
                this.recalculate();
            } catch (e) {
                this.selectedRequirement = null;
            } finally {
                this.loadingRequirement = false;
            }
        }
    }">

        <!-- Page header -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <span x-show="activeType === 'booking'">Create New Booking</span>
                    <span x-show="activeType === 'quotation'" x-cloak>Create New Quotation</span>
                </h1>
                <p class="mt-1 text-sm text-gray-500">Customer ki saved requirement se booking/quotation banayein.</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Segmented switch -->
                <div class="inline-flex rounded-xl border border-gray-200 bg-gray-100 p-1">
                    <button type="button" @click="activeType = 'booking'"
                        :class="activeType === 'booking' ? 'bg-white text-sky-700 shadow-sm ring-1 ring-gray-200' : 'text-gray-500 hover:text-gray-700'"
                        class="inline-flex items-center gap-1.5 rounded-lg px-5 py-1.5 text-sm font-semibold transition">
                        Booking
                    </button>
                    <button type="button" @click="activeType = 'quotation'"
                        :class="activeType === 'quotation' ? 'bg-white text-sky-700 shadow-sm ring-1 ring-gray-200' : 'text-gray-500 hover:text-gray-700'"
                        class="inline-flex items-center gap-1.5 rounded-lg px-5 py-1.5 text-sm font-semibold transition">
                        Quotation
                    </button>
                </div>
                <a href="{{ route('quotations.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Quotations &amp; Bookings
                </a>
            </div>
        </div>

        <form method="POST" action="{{ $quotation ? route('quotations.update', $quotation) : route('quotations.store') }}" @submit.prevent="if (validateBookingForm({customerId: customerId, service: service, selectedRequirement: selectedRequirement})) { $el.submit(); }">
            @csrf
            @if ($quotation)
                @method('PUT')
            @endif

            <input type="hidden" name="type" :value="activeType">

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- ============ LEFT MAIN COLUMN ============ -->
                <div class="space-y-6 lg:col-span-2">
                    <!-- Section 1: Customer & Service -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <h3 class="text-base font-semibold text-gray-900">Customer &amp; Service</h3>
                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="customer_id" value="Select Customer *" />
                                <select id="customer_id" name="customer_id" x-model="customerId" @change="fetchRequirement()"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone ?: 'no phone' }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('customer_id')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="service" value="Select Service *" />
                                <select id="service" name="service" x-model="service" @change="fetchRequirement()"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500" required>
                                    <option value="">Select Service</option>
                                    @foreach ($serviceTypes as $st)
                                        <option value="{{ $st }}" @selected(($quotation ? ($quotation->services_list[0] ?? '') : old('service')) === $st)>{{ $st }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('service')" class="mt-1" />
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Saved Service Requirement -->
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-base font-semibold text-gray-900">Saved Service Requirement</h3>
                            <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-medium text-sky-600">Auto from Services module</span>
                        </div>

                        <div x-show="loadingRequirement" class="mt-4 flex items-center gap-2 py-6 text-sm text-gray-400">
                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Fetching requirement...
                        </div>

                        <div x-show="!loadingRequirement && !selectedRequirement" class="mt-4 rounded-lg border border-dashed border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500">
                            Create a requirement first from the <a href="{{ route('services.create') }}" class="font-semibold text-sky-600 hover:underline">Services module</a> — it will appear here automatically.
                        </div>

                        <div x-show="!loadingRequirement && selectedRequirement" x-transition class="mt-4 space-y-4">
                            <input type="hidden" name="customer_service_id" :value="selectedRequirement ? selectedRequirement.id : ''">
                            <input type="hidden" name="destination" :value="selectedRequirement ? selectedRequirement.destination : ''">

                            <!-- General Information -->
                            <div class="overflow-hidden rounded-lg border border-sky-100">
                                <div class="flex items-center justify-between gap-3 border-b border-sky-100 bg-sky-50/60 px-4 py-2.5">
                                    <span class="text-sm font-semibold text-sky-800">General Information</span>
                                    <span class="inline-flex items-center rounded-full bg-sky-100 px-2 py-0.5 text-xs font-medium text-sky-700" x-text="selectedRequirement.service_type"></span>
                                </div>
                                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 bg-white p-4 sm:grid-cols-3">
                                    <template x-for="row in (selectedRequirement.general || [])" :key="row.label">
                                        <div>
                                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400" x-text="row.label"></dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-700" x-text="row.value || '—'"></dd>
                                        </div>
                                    </template>
                                </dl>
                                <div class="border-t border-sky-100 bg-sky-50/40 px-4 py-2" x-show="selectedRequirement.requirements">
                                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Requirement Details</dt>
                                    <dd class="mt-1 whitespace-pre-line text-sm leading-relaxed text-gray-700" x-text="selectedRequirement.requirements"></dd>
                                </div>
                            </div>

                            <!-- Service-specific requirements -->
                            <div class="overflow-hidden rounded-lg border border-sky-100" x-show="(selectedRequirement.specific || []).length">
                                <div class="flex items-center justify-between gap-3 border-b border-sky-100 bg-sky-50/60 px-4 py-2.5">
                                    <span class="text-sm font-semibold text-sky-800" x-text="selectedRequirement.service_type + ' Requirements'"></span>
                                </div>
                                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 bg-white p-4 sm:grid-cols-2">
                                    <template x-for="row in (selectedRequirement.specific || [])" :key="row.label">
                                        <div :class="String(row.value).length > 60 ? 'sm:col-span-2' : ''">
                                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400" x-text="row.label"></dt>
                                            <dd class="mt-1 whitespace-pre-line text-sm text-gray-700" x-text="row.value"></dd>
                                        </div>
                                    </template>
                                </dl>
                            </div>

                            <!-- Other saved services of this customer -->
                            <div x-show="allServices.filter(s => !s.selected).length" class="overflow-hidden rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between gap-3 border-b border-gray-100 bg-gray-50/60 px-4 py-2.5">
                                    <span class="text-sm font-semibold text-gray-700">Other Saved Services ({{ count($serviceTypes) }})</span>
                                </div>
                                <div class="divide-y divide-gray-100 bg-white">
                                    <template x-for="s in allServices.filter(x => !x.selected)" :key="s.id">
                                        <button type="button" @click="service = s.service_type; fetchRequirement()"
                                            class="flex w-full items-center justify-between gap-3 px-4 py-3 text-left transition hover:bg-gray-50">
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-semibold text-gray-800" x-text="s.service_type"></span>
                                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600" x-text="s.status || '—'"></span>
                                                </div>
                                                <p class="mt-0.5 truncate text-xs text-gray-400" x-text="[s.destination, s.travel_date, s.travelers ? (s.travelers + ' travelers') : ''].filter(Boolean).join(' · ') || 'No details saved'"></p>
                                            </div>
                                            <span class="shrink-0 text-sm font-semibold text-sky-600" x-text="fmt(s.total)"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============ RIGHT COLUMN: Payment Information ============ -->
                <div class="space-y-6">
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-base font-semibold text-gray-900">Payment Information</h3>
                            <span x-show="activeType === 'booking'" class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-600">Booking</span>
                            <span x-show="activeType === 'quotation'" x-cloak class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-medium text-sky-600">Pricing</span>
                        </div>
                        <div class="mt-4 space-y-4">
                            <div>
                                <x-input-label for="members" value="Members / Passengers" />
                                <input id="members" type="number" min="1" x-model.number="members" @input="recalculate()"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                            </div>

                            <div>
                                <x-input-label value="Per-Person Service Price" />
                                <div class="mt-1.5 flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-3 py-2 text-sm">
                                    <span class="truncate text-gray-500" x-text="service || '—'"></span>
                                    <span class="ml-3 shrink-0 font-semibold text-gray-900" x-text="fmt(unitAmount)"></span>
                                </div>
                            </div>

                            <div>
                                <x-input-label value="Total Service Amount (Auto)" />
                                <div class="relative mt-1.5">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm font-medium text-gray-400">Rs.</span>
                                    <input type="number" readonly :value="totalServiceAmount" step="0.01"
                                        class="block w-full rounded-lg border-gray-200 bg-gray-50 !py-1.5 pl-10 text-sm shadow-sm">
                                </div>
                            </div>

                            <div>
                                <x-input-label value="Discount (Enter Amount)" />
                                <div class="relative mt-1.5">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm font-medium text-gray-400">Rs.</span>
                                    <input type="number" name="discount" min="0" step="0.01" x-model.number="discount"
                                        class="block w-full rounded-lg border-gray-300 !py-1.5 pl-10 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                </div>
                                <x-input-error :messages="$errors->get('discount')" class="mt-1" />
                            </div>

                            <!-- Booking & Quotation: Final Total -->
                            <div>
                                <x-input-label value="Final Total (Auto)" />
                                <div class="relative mt-1.5">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm font-medium text-gray-400">Rs.</span>
                                    <input type="number" readonly :value="finalTotal" step="0.01"
                                        class="block w-full rounded-lg border-gray-200 bg-gray-50 !py-1.5 pl-10 text-sm shadow-sm">
                                </div>
                            </div>

                            <!-- Booking & Quotation: Advance (editable, linked to Payment) -->
                            <div>
                                <x-input-label value="Advance (Auto)" />
                                <div class="relative mt-1.5">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-sm font-medium text-gray-400">Rs.</span>
                                    <input type="number" name="advance" min="0" step="0.01" :value="advance" @input="setAdvance($event.target.value)"
                                        class="block w-full rounded-lg border-gray-300 !py-1.5 pl-10 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                </div>
                            </div>

                            <!-- Booking only: Select Account + Payment Amount in one row -->
                            <div x-show="activeType === 'booking'">
                                <x-input-label value="Select Account + Payment Amount" />
                                <div class="mt-1.5 flex flex-wrap items-center gap-2">
                                    <select name="account_id" x-model="account" class="block min-w-0 flex-1 rounded-lg border-gray-300 !py-1.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                        <option value="">Select Account</option>
                                        @foreach ($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="relative w-28 shrink-0">
                                        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-2.5 text-xs font-medium text-gray-400">Rs.</span>
                                        <input type="number" name="payment_amount" min="0" step="0.01" :value="paymentAmount" @input="setPaymentAmount($event.target.value)"
                                            class="block w-full rounded-lg border-gray-300 !py-1.5 pl-8 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Remaining Amount -->
                            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm">
                                <span class="font-medium text-gray-600">Remaining Amount (Auto)</span>
                                <span class="font-semibold text-sky-700" x-text="fmt(remaining)"></span>
                            </div>
                        </div>

                        <input type="hidden" name="subtotal" :value="totalServiceAmount">
                        <input type="hidden" name="grand_total" :value="finalTotal">
                    </div>
                </div>
            </div>

            <!-- Hidden defaults (auto set on save) -->
            <input type="hidden" name="status" :value="activeType === 'booking' ? 'Pending' : 'Draft'">
            <input type="hidden" name="valid_until" :value="activeType === 'booking' ? '' : (validUntil)">
            <input type="hidden" name="reference" :value="activeType === 'booking' ? '' : (reference)">

            <!-- Footer actions -->
            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-gray-200 pt-5 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('quotations.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    <span x-show="activeType === 'booking'">Save Booking</span>
                    <span x-show="activeType === 'quotation'" x-cloak>Save Quotation</span>
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>