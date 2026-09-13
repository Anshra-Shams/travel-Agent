<x-admin-layout title="Add Service Requirement">
    <div x-data="{
        opencModal: false,
        saving: false,
        error: '',
        form: { name: '', phone: '', email: '', address: '', status: 'Active' },
        selectedServices: @js($preselectedService ? [$preselectedService] : []),
        serviceSearch: '',
        serviceDropdownOpen: false,
        availableServices: @js(\App\Models\ServiceType::getActiveNames()),
        openModal() {
            this.opencModal = true;
            this.error = '';
        },
        closeModal() {
            this.opencModal = false;
        },
        addService(service) {
            if (!this.selectedServices.includes(service)) this.selectedServices.push(service);
            this.serviceSearch = '';
            this.serviceDropdownOpen = false;
        },
        removeService(service) {
            this.selectedServices = this.selectedServices.filter(s => s !== service);
        },
        get filteredServices() {
            const q = this.serviceSearch.toLowerCase();
            return this.availableServices.filter(s => s.toLowerCase().includes(q) && !this.selectedServices.includes(s));
        },
        submitCustomer() {
            if (!this.form.name.trim() || !this.form.phone.trim()) {
                this.error = 'Full Name aur Phone Number required hain.';
                return;
            }
            this.saving = true;
            this.error = '';
            const payload = new FormData();
            payload.append('name', this.form.name);
            payload.append('phone', this.form.phone);
            payload.append('email', this.form.email);
            payload.append('address', this.form.address);
            payload.append('status', this.form.status);
            payload.append('_token', document.querySelector('meta[name=csrf-token]').content);
            this.selectedServices.forEach(s => payload.append('service[]', s));
            fetch('{{ route('customers.quick-store') }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: payload
            }).then(async (res) => {
                const data = await res.json();
                if (!res.ok) {
                    const msgs = Object.values(data.errors || {}).flat();
                    this.error = msgs.length ? msgs.join(', ') : 'Kuch galat ho gaya.';
                    return;
                }
                window.dispatchEvent(new CustomEvent('customer-created', { detail: data }));
                this.form = { name: '', phone: '', email: '', address: '', status: 'Active' };
                this.selectedServices = @js($preselectedService ? [$preselectedService] : []);
                this.opencModal = false;
            }).catch(() => {
                this.error = 'Network error. Dobara try karein.';
            }).finally(() => {
                this.saving = false;
            });
        }
    }">
        <div class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Add Service Requirement</h1>
                        <p class="mt-0.5 text-sm text-gray-500">
                            @if ($preselectedService)
                                Record <span class="font-medium text-gray-700">{{ $preselectedService }}</span> requirements for a customer.
                            @else
                                Record a service and its requirements for an existing customer.
                            @endif
                        </p>
                    </div>
                </div>
                <button type="button" @click="openModal()" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Add Customer
                </button>
            </div>
        </div>

        @include('services.partials.form', [
            'action' => route('services.store'),
            'submitLabel' => 'Save Requirement',
            'back' => route('services.index'),
            'selectedCustomerId' => $selectedCustomerId,
            'preselectedService' => $preselectedService,
            'hideStatus' => true,
        ])

        <!-- Quick Add Customer Modal -->
        <div x-show="opencModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div @click="closeModal()" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-xl" @keydown.escape.window="closeModal()">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900">Add New Customer</h3>
                        <button type="button" @click="closeModal()" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="px-6 py-5">
                        <p x-show="error" x-cloak class="mb-4 rounded-lg bg-rose-50 px-4 py-2.5 text-sm text-rose-700 ring-1 ring-inset ring-rose-100" x-text="error"></p>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <x-input-label for="q_name" value="Full Name *" />
                                <x-text-input id="q_name" class="mt-1.5 block w-full !py-1.5" type="text" x-model="form.name" placeholder="e.g. Ahmed Khan" required />
                            </div>
                            <div>
                                <x-input-label for="q_phone" value="Phone Number *" />
                                <x-text-input id="q_phone" class="mt-1.5 block w-full !py-1.5" type="text" x-model="form.phone" placeholder="e.g. +92 300 1234567" required />
                            </div>
                            <div>
                                <x-input-label for="q_email" value="Email" />
                                <x-text-input id="q_email" class="mt-1.5 block w-full !py-1.5" type="email" x-model="form.email" placeholder="customer@example.com" />
                            </div>
                            <div>
                                <x-input-label for="q_address" value="Address" />
                                <x-text-input id="q_address" class="mt-1.5 block w-full !py-1.5" type="text" x-model="form.address" placeholder="Full address (street, area, city)" />
                            </div>

                            <div class="relative" @click.away="serviceDropdownOpen = false">
                                <x-input-label for="q_service" value="Services Interested" />
                                <div class="mt-1.5 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus-within:border-sky-500 focus-within:ring-1 focus-within:ring-sky-500" @click="serviceDropdownOpen = true">
                                    <div class="flex flex-wrap gap-1.5" x-show="selectedServices.length > 0">
                                        <template x-for="svc in selectedServices" :key="'q-svc-' + svc">
                                            <span class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-medium text-sky-700">
                                                <span x-text="svc"></span>
                                                <button type="button" @click.prevent="removeService(svc)" class="ml-0.5 rounded-full p-0.5 hover:bg-sky-200">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                </button>
                                            </span>
                                        </template>
                                    </div>
                                    <input type="text" x-model="serviceSearch" @focus="serviceDropdownOpen = true" placeholder="Search services..." class="mt-1 w-full border-0 p-0 text-sm text-gray-700 shadow-none focus:ring-0 focus:outline-none" x-show="serviceDropdownOpen" />
                                    <p x-show="!serviceDropdownOpen && selectedServices.length === 0" class="cursor-text text-sm text-gray-400">Select services...</p>
                                </div>
                                <div x-show="serviceDropdownOpen && filteredServices.length > 0" x-transition class="absolute z-30 mt-1 w-full overflow-hidden rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                                    <template x-for="svc in filteredServices" :key="'q-drop-' + svc">
                                        <button type="button" @click.prevent="addService(svc)" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-sky-50 hover:text-sky-700" x-text="svc"></button>
                                    </template>
                                </div>
                                <div x-show="serviceDropdownOpen && filteredServices.length === 0 && serviceSearch.length > 0" class="absolute z-30 mt-1 w-full rounded-lg border border-gray-200 bg-white py-3 text-center text-sm text-gray-400 shadow-lg">
                                    No matching services
                                </div>
                            </div>

                            <div>
                                <x-input-label for="q_status" value="Customer Status" />
                                <select id="q_status" x-model="form.status" class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                            <button type="button" @click="closeModal()" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="button" @click="submitCustomer()" :disabled="saving" class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-60">
                                <span x-show="saving" x-cloak class="mr-2 inline-block h-3.5 w-3.5 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                                <span x-text="saving ? 'Saving...' : 'Save Customer'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>