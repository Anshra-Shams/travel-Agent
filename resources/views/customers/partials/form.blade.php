@php
    $customer = $customer ?? null;
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method ?? null)
        @method($method)
    @endif

    <!-- Personal information -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Personal Information</h3>
        <p class="mt-0.5 text-sm text-gray-500">Basic personal details of the customer.</p>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="name" value="Full Name" />
                <x-text-input id="name" class="mt-1.5 block w-full" type="text" name="name" :value="old('name', $customer?->name)" required autofocus placeholder="e.g. Ahmed Khan" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="phone" value="Phone" />
                <x-text-input id="phone" class="mt-1.5 block w-full" type="text" name="phone" :value="old('phone', $customer?->phone)" required placeholder="e.g. +92 300 1234567" />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="whatsapp" value="WhatsApp Number" />
                <x-text-input id="whatsapp" class="mt-1.5 block w-full" type="text" name="whatsapp" :value="old('whatsapp', $customer?->whatsapp)" placeholder="e.g. +92 300 1234567" />
                <x-input-error :messages="$errors->get('whatsapp')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1.5 block w-full" type="email" name="email" :value="old('email', $customer?->email)" placeholder="customer@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="gender" value="Gender" />
                <select id="gender" name="gender" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    <option value="">Select gender</option>
                    @foreach (['Male', 'Female', 'Other'] as $gender)
                        <option value="{{ $gender }}" @selected(old('gender', $customer?->gender) === $gender)>{{ $gender }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('gender')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="date_of_birth" value="Date of Birth" />
                <x-text-input id="date_of_birth" class="mt-1.5 block w-full" type="date" name="date_of_birth" :value="old('date_of_birth', $customer?->date_of_birth?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-1" />
            </div>
            <div class="sm:col-span-2">
                <x-input-label for="address" value="Address" />
                <x-text-input id="address" class="mt-1.5 block w-full" type="text" name="address" :value="old('address', $customer?->address)" placeholder="Street, city, area..." />
                <x-input-error :messages="$errors->get('address')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="country" value="Country" />
                <x-text-input id="country" class="mt-1.5 block w-full" type="text" name="country" :value="old('country', $customer?->country)" placeholder="e.g. Pakistan" />
                <x-input-error :messages="$errors->get('country')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Passport / CNIC information -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Passport / CNIC Information</h3>
        <p class="mt-0.5 text-sm text-gray-500">Identity documents required for travel services.</p>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="cnic" value="CNIC" />
                <x-text-input id="cnic" class="mt-1.5 block w-full" type="text" name="cnic" :value="old('cnic', $customer?->cnic)" placeholder="e.g. 42101-1234567-8" />
                <x-input-error :messages="$errors->get('cnic')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="passport_number" value="Passport Number" />
                <x-text-input id="passport_number" class="mt-1.5 block w-full" type="text" name="passport_number" :value="old('passport_number', $customer?->passport_number)" placeholder="e.g. AB1234567" />
                <x-input-error :messages="$errors->get('passport_number')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="passport_expiry" value="Passport Expiry" />
                <x-text-input id="passport_expiry" class="mt-1.5 block w-full" type="date" name="passport_expiry" :value="old('passport_expiry', $customer?->passport_expiry?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('passport_expiry')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Travel / service information -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Travel / Service Information</h3>
        <p class="mt-0.5 text-sm text-gray-500">Services the customer is interested in.</p>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <x-input-label for="service" value="Services" />
                <p class="mt-1 text-xs text-gray-500">Select one or more services this customer is interested in.</p>
                @php
                    $selectedServices = (array) old('service', $customer?->services_list ?: []);
                @endphp
                <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($services as $service)
                        <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 px-3 py-2.5 text-sm text-gray-700 transition hover:border-sky-300 hover:bg-sky-50/50">
                            <input type="checkbox" name="service[]" value="{{ $service }}" @checked(in_array($service, $selectedServices)) class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                            {{ $service }}
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('service')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="destination" value="Travel Destination" />
                <x-text-input id="destination" class="mt-1.5 block w-full" type="text" name="destination" :value="old('destination', $customer?->destination)" placeholder="e.g. Makkah, Istanbul, Dubai" />
                <x-input-error :messages="$errors->get('destination')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="travel_date" value="Travel Date" />
                <x-text-input id="travel_date" class="mt-1.5 block w-full" type="date" name="travel_date" :value="old('travel_date', $customer?->travel_date?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('travel_date')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="travelers" value="Number of Travelers" />
                <x-text-input id="travelers" class="mt-1.5 block w-full" type="number" name="travelers" min="1" max="999" :value="old('travelers', $customer?->travelers ?? 1)" />
                <x-input-error :messages="$errors->get('travelers')" class="mt-1" />
            </div>
            <div class="sm:col-span-2">
                <x-input-label for="source" value="Lead Source" />
                <x-text-input id="source" class="mt-1.5 block w-full" type="text" name="source" :value="old('source', $customer?->source)" placeholder="e.g. Facebook, Website, Referral" />
                <x-input-error :messages="$errors->get('source')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Status & notes -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Status & Notes</h3>
        <p class="mt-0.5 text-sm text-gray-500">Customer status and additional information.</p>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    @foreach (['Active', 'Inactive'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $customer?->status ?? 'Active') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>
            @if ($customer)
                <div>
                    <x-input-label value="Customer Source" />
                    <input type="text" value="{{ $customer->source_label }}" disabled class="mt-1.5 block w-full cursor-not-allowed rounded-lg border-gray-200 bg-gray-50 text-sm text-gray-500 shadow-sm" />
                </div>
            @endif
        </div>

        <div class="mt-4">
            <x-input-label for="notes" value="Notes" />
            <textarea id="notes" name="notes" rows="4" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Write any additional notes here...">{{ old('notes', $customer?->notes) }}</textarea>
            <x-input-error :messages="$errors->get('notes')" class="mt-1" />
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ $back ?? route('customers.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            {{ $submitLabel }}
        </button>
    </div>
</form>
