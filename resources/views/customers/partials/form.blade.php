@php
    $customer = $customer ?? null;
@endphp

<form method="POST" action="{{ $action }}" class="space-y-3" x-data="{
    availableServices: @js($services),
    selectedServices: @js($customer ? $customer->services_list : []),
    serviceSearch: '',
    serviceDropdownOpen: false,
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
    }
}">
    @csrf
    @if ($method ?? null)
        @method($method)
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <!-- Row 1: Full Name + Phone -->
            <div>
                <x-input-label for="name" value="Full Name *" />
                <x-text-input id="name" class="mt-1.5 block w-full !py-1.5" type="text" name="name" :value="old('name', $customer?->name)" required placeholder="e.g. Ahmed Khan" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="phone" value="Phone Number *" />
                <x-text-input id="phone" class="mt-1.5 block w-full !py-1.5" type="text" name="phone" :value="old('phone', $customer?->phone)" required placeholder="e.g. +92 300 1234567" />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <!-- Row 2: Email + Address -->
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1.5 block w-full !py-1.5" type="email" name="email" :value="old('email', $customer?->email)" placeholder="customer@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="address" value="Address" />
                <textarea id="address" name="address" rows="2" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Full address (street, area, city)">{{ old('address', $customer?->address) }}</textarea>
                <x-input-error :messages="$errors->get('address')" class="mt-1" />
            </div>

            <!-- Row 3: Services Interested + Customer Status -->
            <div class="relative" @click.away="serviceDropdownOpen = false">
                <x-input-label for="service" value="Services Interested" />
                <div class="mt-1.5 rounded-lg border border-gray-300 bg-white p-2 shadow-sm focus-within:border-sky-500 focus-within:ring-1 focus-within:ring-sky-500" @click="serviceDropdownOpen = true">
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
                    <input type="text" x-model="serviceSearch" @focus="serviceDropdownOpen = true" placeholder="Search services..." class="mt-1 w-full border-0 p-0 text-sm text-gray-700 focus:ring-0 focus:outline-none" x-show="serviceDropdownOpen">
                    <p x-show="!serviceDropdownOpen && selectedServices.length === 0" class="cursor-text text-sm text-gray-400">Select services...</p>
                </div>
                <template x-for="svc in selectedServices" :key="'input-' + svc">
                    <input type="hidden" name="service[]" :value="svc">
                </template>
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
                <x-input-label for="status" value="Customer Status" />
                <select id="status" name="status" class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    @foreach (['Active', 'Inactive'] as $s)
                        <option value="{{ $s }}" @selected(old('status', $customer?->status ?? 'Active') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ $back ?? route('customers.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            {{ $submitLabel }}
        </button>
    </div>
</form>