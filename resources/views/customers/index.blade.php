<x-admin-layout title="Customers">
    <div class="space-y-6" x-data="{
        showModal: @js($errors->any()),
        availableServices: @js($services),
        selectedServices: @js(old('service', [])),
        serviceSearch: '',
        serviceDropdownOpen: false,
        modalMode: 'add',
        editingCustomer: null,
        form: @js([
            'name' => old('name', ''),
            'phone' => old('phone', ''),
            'email' => old('email', ''),
            'address' => old('address', ''),
            'status' => old('status', 'Active'),
        ]),
        openModal() {
            this.modalMode = 'add';
            this.editingCustomer = null;
            this.form = { name: '', phone: '', email: '', address: '', status: 'Active' };
            this.selectedServices = [];
            this.serviceSearch = '';
            this.serviceDropdownOpen = false;
            this.showModal = true;
        },
        openEditModal(customer) {
            this.modalMode = 'edit';
            this.editingCustomer = customer;
            this.form = {
                name: customer.name,
                phone: customer.phone,
                email: customer.email || '',
                address: customer.address || '',
                status: customer.status || 'Active'
            };
            this.selectedServices = [...(customer.services_list || [])];
            this.serviceSearch = '';
            this.serviceDropdownOpen = false;
            this.showModal = true;
        },
        addService(service) {
            if (!this.selectedServices.includes(service)) {
                this.selectedServices.push(service);
            }
            this.serviceSearch = '';
            this.serviceDropdownOpen = false;
        },
        removeService(service) {
            this.selectedServices = this.selectedServices.filter(s => s !== service);
        },
        get filteredServices() {
            const q = this.serviceSearch.toLowerCase();
            return this.availableServices.filter(s =>
                s.toLowerCase().includes(q) && !this.selectedServices.includes(s)
            );
        },
        csrfToken: @js(csrf_token()),
        menuOpen: false,
        menuStyle: '',
        menuData: { view: '', edit: {}, destroy: '' },
        openMenu(btn, data) {
            const r = btn.getBoundingClientRect();
            const w = 195;
            const estimate = 3 * 40 + 16;
            let top = r.bottom + 4;
            if (window.innerHeight - top < estimate) {
                top = Math.max(8, r.top - estimate - 4);
            }
            const right = Math.max(8, Math.min(window.innerWidth - r.right, w));
            this.menuStyle = 'position: fixed; top:' + top + 'px; right:' + right + 'px; width:' + w + 'px;';
            this.menuData = data;
            this.menuOpen = true;
        }
    }">

        <!-- Page header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
                <p class="mt-1 text-sm text-gray-500">Manage and view all customer records</p>
            </div>
            <button type="button" @click="openModal()" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Customer
            </button>
        </div>

        <!-- Toolbar -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('customers.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by Name, Phone, Customer ID or Passport No." class="w-full rounded-lg border-gray-300 py-2.5 pl-10 pr-3 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <select name="status" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">All Status</option>
                        <option value="Active" @selected(($filters['status'] ?? '') === 'Active')>Active</option>
                        <option value="Inactive" @selected(($filters['status'] ?? '') === 'Inactive')>Inactive</option>
                    </select>

                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    @if ($customers->total() > 0 && ($filters['search'] ?? $filters['status'] ?? null))
                        <a href="{{ route('customers.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Customers table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Customer ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Customer Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Phone Number</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Interested Services</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Source</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Created Date</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($customers as $customer)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <span class="text-sm font-medium text-gray-900">#{{ $customer->id }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </span>
                                        <div class="min-w-0">
                                            <a href="{{ route('customers.show', $customer) }}" class="truncate text-sm font-medium text-gray-900 hover:text-sky-600">{{ $customer->name }}</a>
                                            @if ($customer->email)
                                                <p class="truncate text-xs text-gray-400">{{ $customer->email }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $customer->phone }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    @php $svcs = $customer->services_list; @endphp
                                    @if (count($svcs) > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach (array_slice($svcs, 0, 3) as $svc)
                                                <span class="inline-flex items-center rounded-full bg-sky-50 px-2 py-0.5 text-[11px] font-medium text-sky-700 ring-1 ring-inset ring-sky-200">{{ $svc }}</span>
                                            @endforeach
                                            @if (count($svcs) > 3)
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-500">+{{ count($svcs) - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if ($customer->status === 'Active')
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">Active</span>
                                    @else
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-200">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if ($customer->customer_source === 'lead')
                                        <span class="inline-flex items-center gap-1 whitespace-nowrap rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-200">
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4M16 17H4m0 0l4 4m-4-4l4-4" /></svg>
                                            Lead
                                        </span>
                                    @else
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200">Direct</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500">{{ $customer->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end">
                                        <button type="button" @click="openMenu($el, @js([
                                            'view' => route('customers.show', $customer),
                                            'destroy' => route('customers.destroy', $customer),
                                            'edit' => [
                                                'id' => $customer->id,
                                                'name' => $customer->name,
                                                'phone' => $customer->phone,
                                                'email' => $customer->email,
                                                'address' => $customer->address,
                                                'status' => $customer->status,
                                                'services_list' => $customer->services_list,
                                            ],
                                        ]))" class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700" title="Actions">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-16 text-center">
                                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                                        <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                                        </svg>
                                    </span>
                                    <p class="mt-4 text-sm font-medium text-gray-500">No customers found</p>
                                    <p class="mt-1 text-xs text-gray-400">Add your first customer or adjust the filters.</p>
                                    <button type="button" @click="openModal()" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Customer
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($customers->hasPages() || $customers->total() > 0)
                <div class="border-t border-gray-200 px-4 py-3">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-gray-500">
                            Showing <span class="font-medium text-gray-700">{{ $customers->firstItem() ?: 0 }}</span> to <span class="font-medium text-gray-700">{{ $customers->lastItem() ?: 0 }}</span> of <span class="font-medium text-gray-700">{{ $customers->total() }}</span> customers
                        </p>
                        {{ $customers->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Customer Modal (Add / Edit) -->
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div @click="showModal = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-xl" @keydown.escape.window="showModal = false">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900" x-text="modalMode === 'edit' ? 'Edit New Customer' : 'Add New Customer'"></h3>
                        <button type="button" @click="showModal = false" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <form method="POST" :action="modalMode === 'edit' ? '/customers/' + editingCustomer.id : '{{ route('customers.store') }}'" class="px-6 py-5">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH" :disabled="modalMode !== 'edit'" />

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <!-- Row 1: Full Name + Phone -->
                            <div>
                                <x-input-label for="modal_name" value="Full Name *" />
                                <x-text-input id="modal_name" class="mt-1.5 block w-full !py-1.5" type="text" name="name" x-model="form.name" placeholder="e.g. Ahmed Khan" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="modal_phone" value="Phone Number *" />
                                <x-text-input id="modal_phone" class="mt-1.5 block w-full !py-1.5" type="text" name="phone" x-model="form.phone" placeholder="e.g. +92 300 1234567" required />
                                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                            </div>

                            <!-- Row 2: Email + Address -->
                            <div>
                                <x-input-label for="modal_email" value="Email" />
                                <x-text-input id="modal_email" class="mt-1.5 block w-full !py-1.5" type="email" name="email" x-model="form.email" placeholder="customer@example.com" />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="modal_address" value="Address" />
                                <x-text-input id="modal_address" class="mt-1.5 block w-full !py-1.5" type="text" name="address" x-model="form.address" placeholder="Full address (street, area, city)" />
                                <x-input-error :messages="$errors->get('address')" class="mt-1" />
                            </div>

                            <!-- Row 3: Services Interested + Customer Status -->
                            <div class="relative" @click.away="serviceDropdownOpen = false">
                                <x-input-label for="modal_service" value="Services Interested" />
                                <div class="mt-1.5 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus-within:border-sky-500 focus-within:ring-1 focus-within:ring-sky-500" @click="serviceDropdownOpen = true">
                                    <!-- Selected tags -->
                                    <div class="flex flex-wrap gap-1.5" x-show="selectedServices.length > 0">
                                        <template x-for="svc in selectedServices" :key="svc">
                                            <span class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-2.5 py-0.5 text-xs font-medium text-sky-700">
                                                <span x-text="svc"></span>
                                                <button type="button" @click.prevent="removeService(svc)" class="ml-0.5 rounded-full p-0.5 hover:bg-sky-200">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                                </button>
                                            </span>
                                        </template>
                                    </div>
                                    <!-- Search input (only when dropdown open) -->
                                    <input type="text" x-model="serviceSearch" @focus="serviceDropdownOpen = true" placeholder="Search services..." class="mt-1 w-full border-0 p-0 text-sm text-gray-700 shadow-none focus:ring-0 focus:outline-none" x-show="serviceDropdownOpen" />
                                    <!-- Placeholder when closed & nothing selected -->
                                    <p x-show="!serviceDropdownOpen && selectedServices.length === 0" class="cursor-text text-sm text-gray-400">Select services...</p>
                                </div>
                                <!-- Hidden inputs for form submission -->
                                <template x-for="svc in selectedServices" :key="'input-' + svc">
                                    <input type="hidden" name="service[]" :value="svc" />
                                </template>
                                <!-- Dropdown -->
                                <div x-show="serviceDropdownOpen && filteredServices.length > 0" x-transition class="absolute z-30 mt-1 w-full overflow-hidden rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                                    <template x-for="svc in filteredServices" :key="svc">
                                        <button type="button" @click.prevent="addService(svc)" class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-sky-50 hover:text-sky-700" x-text="svc"></button>
                                    </template>
                                </div>
                                <div x-show="serviceDropdownOpen && filteredServices.length === 0 && serviceSearch.length > 0" class="absolute z-30 mt-1 w-full rounded-lg border border-gray-200 bg-white py-3 text-center text-sm text-gray-400 shadow-lg">
                                    No matching services
                                </div>
                                <x-input-error :messages="$errors->get('service')" class="mt-1" />
                            </div>

                            <div>
                                <x-input-label for="modal_status" value="Customer Status" />
                                <select id="modal_status" name="status" x-model="form.status" class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                            <button type="button" @click="showModal = false" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2" x-text="modalMode === 'edit' ? 'Update Customer' : 'Save Customer'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- Floating action dropdown (teleported to body, no table scroll) -->
        <template x-teleport="body">
            <div x-show="menuOpen" x-cloak x-transition :style="menuStyle" class="overflow-hidden rounded-lg border border-gray-200 bg-white py-1 shadow-xl" @click.outside="menuOpen = false" @keydown.escape.window="menuOpen = false">
                <a :href="menuData.view" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50 hover:text-sky-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    View Profile
                </a>
                <button type="button" @click="openEditModal(menuData.edit); menuOpen = false" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50 hover:text-indigo-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </button>
                <form :action="menuData.destroy" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                    <input type="hidden" name="_token" :value="csrfToken" />
                    <input type="hidden" name="_method" value="DELETE" />
                    <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2.5 text-sm text-gray-700 transition hover:bg-gray-50 hover:text-rose-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </template>
    </div>
</x-admin-layout>