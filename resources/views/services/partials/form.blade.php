@php
    $service = $service ?? null;
    $preselectedService = $preselectedService ?? null;
    $currentType = old('service_type', $service?->service_type ?? $preselectedService ?? '');
    $currentCustomerId = (int) old('customer_id', $service?->customer_id ?? $selectedCustomerId ?? 0);
    $isPreselected = (bool) $preselectedService;
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6"
      x-data="{
          customerId: @js($currentCustomerId),
          serviceType: @js($currentType),
          services: [],
          isLoading: false,
          isPreselected: @js($isPreselected),
          init() {
              if (this.isPreselected) {
                  this.services = [@js($preselectedService)];
                  return;
              }
              if (this.customerId) {
                  this.fetchServices();
              }
          },
          fetchServices() {
              if (!this.customerId) {
                  this.services = [];
                  this.serviceType = '';
                  return;
              }
              this.isLoading = true;
              fetch('/customers/' + this.customerId + '/services')
                  .then((response) => response.json())
                  .then((data) => {
                      this.services = data.services || [];
                      if (this.services.length === 1) {
                          this.serviceType = this.services[0];
                          return;
                      }
                      if (this.serviceType && !this.services.includes(this.serviceType)) {
                          this.services = [this.serviceType].concat(this.services);
                      } else if (!this.serviceType || !this.services.includes(this.serviceType)) {
                          this.serviceType = '';
                      }
                  })
                  .finally(() => {
                      this.isLoading = false;
                  });
          }
      }">
    @csrf
    @if ($method ?? null)
        @method($method)
    @endif

    <!-- Customer & service type -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Service Selection</h3>
        <p class="mt-0.5 text-sm text-gray-500">
            @if ($isPreselected)
                Select a customer who has the <span class="font-medium text-gray-700">{{ $preselectedService }}</span> service.
            @else
                Choose a customer to load their services, then record the requirements.
            @endif
        </p>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="service_type" value="Service Type" />
                @if ($isPreselected)
                    <div class="mt-1.5 flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5">
                        <input type="hidden" name="service_type" value="{{ $preselectedService }}">
                        <span class="text-sm font-medium text-gray-700">{{ $preselectedService }}</span>
                        <span class="inline-flex items-center rounded-full bg-sky-50 px-2 py-0.5 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-200">Pre-selected</span>
                    </div>
                @else
                    <select id="service_type" name="service_type" x-model="serviceType" required class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="" x-text="isLoading ? 'Loading services...' : (services.length === 0 && customerId ? 'No services found for this customer' : 'Select a service')"></option>
                        <template x-for="svc in services" :key="svc">
                            <option :value="svc" x-text="svc"></option>
                        </template>
                    </select>
                    <p x-show="customerId && !isLoading && services.length === 0" x-cloak class="mt-2.5 rounded-lg bg-amber-50 px-3 py-2.5 text-xs text-amber-700 ring-1 ring-inset ring-amber-100">
                        This customer has no services saved yet. Edit the customer and select a service first.
                    </p>
                    <p x-show="services.length > 1" x-cloak class="mt-2.5 text-xs text-gray-500">
                        This customer has multiple services — pick the one you want to record requirements for.
                    </p>
                @endif
                <x-input-error :messages="$errors->get('service_type')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="customer_id" value="Customer" />
                <select id="customer_id" name="customer_id" x-model="customerId" @if(!$isPreselected) @change="fetchServices()" @endif required class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    <option value="">{{ $isPreselected ? 'Select a customer with ' . $preselectedService : 'Select a customer' }}</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected((int) old('customer_id', $service?->customer_id ?? $selectedCustomerId ?? 0) === (int) $customer->id)>
                            {{ $customer->name }}{{ $customer->phone ? ' — ' . $customer->phone : '' }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('customer_id')" class="mt-1" />

                @if ($isPreselected && $customers->isEmpty())
                    <p class="mt-2.5 rounded-lg bg-amber-50 px-3 py-2.5 text-xs text-amber-700 ring-1 ring-inset ring-amber-100">
                        No customers found with the "{{ $preselectedService }}" service. Add this service to a customer first.
                    </p>
                @endif
            </div>
        </div>

        <p class="mt-4 rounded-lg bg-sky-50 px-4 py-3 text-xs text-sky-700 ring-1 ring-inset ring-sky-100">
            @if ($isPreselected)
                Service is pre-selected. Pick a customer from the {{ $preselectedService }} customer list, then fill in the requirement details below.
            @else
                Choose a customer to automatically load their saved services. Picking a service reveals the requirement fields for it.
            @endif
        </p>
    </div>

    <!-- Common requirements -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Common Requirements</h3>
        <p class="mt-0.5 text-sm text-gray-500">Basic details shared by every service.</p>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <x-input-label for="destination" value="Destination" />
                <x-text-input id="destination" class="mt-1.5 block w-full" type="text" name="destination" :value="old('destination', $service?->destination)" placeholder="e.g. Makkah, Istanbul, Dubai" />
                <x-input-error :messages="$errors->get('destination')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="travel_date" value="Travel Date" />
                <x-text-input id="travel_date" class="mt-1.5 block w-full" type="date" name="travel_date" :value="old('travel_date', $service?->travel_date?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('travel_date')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="travelers" value="Number of Travelers" />
                <x-text-input id="travelers" class="mt-1.5 block w-full" type="number" name="travelers" min="1" max="999" :value="old('travelers', $service?->travelers)" />
                <x-input-error :messages="$errors->get('travelers')" class="mt-1" />
            </div>
            <div class="sm:col-span-3">
                <x-input-label for="requirements" value="Requirements / Notes" />
                <textarea id="requirements" name="requirements" rows="3" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Write the general requirements or notes for this service...">{{ old('requirements', $service?->requirements) }}</textarea>
                <x-input-error :messages="$errors->get('requirements')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Flight Ticket -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm" x-show="serviceType === 'Flight Ticket'" x-cloak>
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-sky-100 text-sky-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>
            </span>
            Flight Ticket Requirements
        </h3>
        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="departure" value="Departure" />
                <x-text-input id="departure" class="mt-1.5 block w-full" type="text" name="departure" :value="old('departure', $service?->departure)" placeholder="e.g. Karachi (KHI)" />
                <x-input-error :messages="$errors->get('departure')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="arrival" value="Arrival" />
                <x-text-input id="arrival" class="mt-1.5 block w-full" type="text" name="arrival" :value="old('arrival', $service?->arrival)" placeholder="e.g. Jeddah (JED)" />
                <x-input-error :messages="$errors->get('arrival')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="departure_date" value="Departure Date" />
                <x-text-input id="departure_date" class="mt-1.5 block w-full" type="date" name="departure_date" :value="old('departure_date', $service?->departure_date?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('departure_date')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="return_date" value="Return Date" />
                <x-text-input id="return_date" class="mt-1.5 block w-full" type="date" name="return_date" :value="old('return_date', $service?->return_date?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('return_date')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="trip_type" value="Trip Type" />
                <select id="trip_type" name="trip_type" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    <option value="">Select trip type</option>
                    <option value="One Way" @selected(old('trip_type', $service?->trip_type) === 'One Way')>One Way</option>
                    <option value="Round Trip" @selected(old('trip_type', $service?->trip_type) === 'Round Trip')>Round Trip</option>
                    <option value="Multi City" @selected(old('trip_type', $service?->trip_type) === 'Multi City')>Multi City</option>
                </select>
                <x-input-error :messages="$errors->get('trip_type')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="passenger_count" value="Passenger Count" />
                <x-text-input id="passenger_count" class="mt-1.5 block w-full" type="number" name="passenger_count" min="1" max="999" :value="old('passenger_count', $service?->passenger_count)" />
                <x-input-error :messages="$errors->get('passenger_count')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="preferred_airline" value="Preferred Airline" />
                <x-text-input id="preferred_airline" class="mt-1.5 block w-full" type="text" name="preferred_airline" :value="old('preferred_airline', $service?->preferred_airline)" placeholder="e.g. Saudia, Emirates, PIA" />
                <x-input-error :messages="$errors->get('preferred_airline')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="flight_class" value="Class" />
                <select id="flight_class" name="flight_class" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    <option value="">Select class</option>
                    @foreach (['Economy', 'Premium Economy', 'Business', 'First'] as $class)
                        <option value="{{ $class }}" @selected(old('flight_class', $service?->flight_class) === $class)>{{ $class }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('flight_class')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Visa -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm" x-show="serviceType === 'Visa'" x-cloak>
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
            </span>
            Visa Requirements
        </h3>
        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <x-input-label for="visa_country" value="Country" />
                <x-text-input id="visa_country" class="mt-1.5 block w-full" type="text" name="visa_country" :value="old('visa_country', $service?->visa_country)" placeholder="e.g. Saudi Arabia, Turkey" />
                <x-input-error :messages="$errors->get('visa_country')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="visa_type" value="Visa Type" />
                <x-text-input id="visa_type" class="mt-1.5 block w-full" type="text" name="visa_type" :value="old('visa_type', $service?->visa_type)" placeholder="e.g. Tourist, Business, Umrah" />
                <x-input-error :messages="$errors->get('visa_type')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="applicants" value="Number of Applicants" />
                <x-text-input id="applicants" class="mt-1.5 block w-full" type="number" name="applicants" min="1" max="999" :value="old('applicants', $service?->applicants)" />
                <x-input-error :messages="$errors->get('applicants')" class="mt-1" />
            </div>
            <div class="sm:col-span-3">
                <x-input-label for="visa_requirements" value="Visa Requirements" />
                <textarea id="visa_requirements" name="visa_requirements" rows="3" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Documents needed, passport validity, photographs, etc.">{{ old('visa_requirements', $service?->visa_requirements) }}</textarea>
                <x-input-error :messages="$errors->get('visa_requirements')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Hotel -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm" x-show="serviceType === 'Hotel'" x-cloak>
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-rose-100 text-rose-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10M8 21v-6h8v6" /></svg>
            </span>
            Hotel Requirements
        </h3>
        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="hotel_preference" value="Hotel Preference" />
                <x-text-input id="hotel_preference" class="mt-1.5 block w-full" type="text" name="hotel_preference" :value="old('hotel_preference', $service?->hotel_preference)" placeholder="e.g. Near Haram, 5-star, Beachfront" />
                <x-input-error :messages="$errors->get('hotel_preference')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="room_type" value="Room Type" />
                <x-text-input id="room_type" class="mt-1.5 block w-full" type="text" name="room_type" :value="old('room_type', $service?->room_type)" placeholder="e.g. Standard, Deluxe, Suite" />
                <x-input-error :messages="$errors->get('room_type')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="check_in" value="Check-in" />
                <x-text-input id="check_in" class="mt-1.5 block w-full" type="date" name="check_in" :value="old('check_in', $service?->check_in?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('check_in')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="check_out" value="Check-out" />
                <x-text-input id="check_out" class="mt-1.5 block w-full" type="date" name="check_out" :value="old('check_out', $service?->check_out?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('check_out')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="rooms" value="Rooms" />
                <x-text-input id="rooms" class="mt-1.5 block w-full" type="number" name="rooms" min="1" max="99" :value="old('rooms', $service?->rooms)" />
                <x-input-error :messages="$errors->get('rooms')" class="mt-1" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="adults" value="Adults" />
                    <x-text-input id="adults" class="mt-1.5 block w-full" type="number" name="adults" min="1" max="99" :value="old('adults', $service?->adults)" />
                    <x-input-error :messages="$errors->get('adults')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="children" value="Children" />
                    <x-text-input id="children" class="mt-1.5 block w-full" type="number" name="children" min="0" max="99" :value="old('children', $service?->children)" />
                    <x-input-error :messages="$errors->get('children')" class="mt-1" />
                </div>
            </div>
        </div>
    </div>

    <!-- Umrah -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm" x-show="serviceType === 'Umrah'" x-cloak>
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-emerald-100 text-emerald-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646zM8 9h.01M15 9h.01M8 13.5h8" /></svg>
            </span>
            Umrah Requirements
        </h3>
        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <x-input-label for="package_type" value="Package Type" />
                <x-text-input id="package_type" class="mt-1.5 block w-full" type="text" name="package_type" :value="old('package_type', $service?->package_type)" placeholder="e.g. Economy, Standard, VIP" />
                <x-input-error :messages="$errors->get('package_type')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="makkah_hotel" value="Makkah Hotel" />
                <x-text-input id="makkah_hotel" class="mt-1.5 block w-full" type="text" name="makkah_hotel" :value="old('makkah_hotel', $service?->makkah_hotel)" placeholder="e.g. Hilton Suites, Anwar Al Madinah" />
                <x-input-error :messages="$errors->get('makkah_hotel')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="madinah_hotel" value="Madinah Hotel" />
                <x-text-input id="madinah_hotel" class="mt-1.5 block w-full" type="text" name="madinah_hotel" :value="old('madinah_hotel', $service?->madinah_hotel)" placeholder="e.g. Anwar Al Madinah Mövenpick" />
                <x-input-error :messages="$errors->get('madinah_hotel')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="makkah_nights" value="Nights in Makkah" />
                <x-text-input id="makkah_nights" class="mt-1.5 block w-full" type="number" name="makkah_nights" min="0" max="365" :value="old('makkah_nights', $service?->makkah_nights)" />
                <x-input-error :messages="$errors->get('makkah_nights')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="madinah_nights" value="Nights in Madinah" />
                <x-text-input id="madinah_nights" class="mt-1.5 block w-full" type="number" name="madinah_nights" min="0" max="365" :value="old('madinah_nights', $service?->madinah_nights)" />
                <x-input-error :messages="$errors->get('madinah_nights')" class="mt-1" />
            </div>
            <div class="sm:col-span-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Included in Package</span>
                <div class="mt-2 flex flex-wrap gap-6">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="transport_requirement" value="1" @checked(old('transport_requirement', $service?->transport_requirement)) class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                        Transport
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="visa_requirement" value="1" @checked(old('visa_requirement', $service?->visa_requirement)) class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                        Visa
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="ticket_requirement" value="1" @checked(old('ticket_requirement', $service?->ticket_requirement)) class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                        Ticket
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Hajj -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm" x-show="serviceType === 'Hajj'" x-cloak>
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-amber-100 text-amber-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </span>
            Hajj Requirements
        </h3>
        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div>
                <x-input-label for="package_type" value="Package Type" />
                <x-text-input id="package_type" class="mt-1.5 block w-full" type="text" name="package_type" :value="old('package_type', $service?->package_type)" placeholder="e.g. Economy, Standard, VIP" />
                <x-input-error :messages="$errors->get('package_type')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="makkah_hotel" value="Makkah Hotel" />
                <x-text-input id="makkah_hotel" class="mt-1.5 block w-full" type="text" name="makkah_hotel" :value="old('makkah_hotel', $service?->makkah_hotel)" placeholder="e.g. Hilton Suites, Clock Tower" />
                <x-input-error :messages="$errors->get('makkah_hotel')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="madinah_hotel" value="Madinah Hotel" />
                <x-text-input id="madinah_hotel" class="mt-1.5 block w-full" type="text" name="madinah_hotel" :value="old('madinah_hotel', $service?->madinah_hotel)" placeholder="e.g. Anwar Al Madinah Mövenpick" />
                <x-input-error :messages="$errors->get('madinah_hotel')" class="mt-1" />
            </div>
            <div class="sm:col-span-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Included in Package</span>
                <div class="mt-2 flex flex-wrap gap-6">
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="transport_requirement" value="1" @checked(old('transport_requirement', $service?->transport_requirement)) class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                        Transport
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="visa_requirement" value="1" @checked(old('visa_requirement', $service?->visa_requirement)) class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                        Visa
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="ticket_requirement" value="1" @checked(old('ticket_requirement', $service?->ticket_requirement)) class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                        Ticket
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Worldwide Tour Package -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm" x-show="serviceType === 'Worldwide Tour Package'" x-cloak>
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-cyan-100 text-cyan-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
            </span>
            Tour Package Requirements
        </h3>
        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="package_type" value="Package Type" />
                <x-text-input id="package_type" class="mt-1.5 block w-full" type="text" name="package_type" :value="old('package_type', $service?->package_type)" placeholder="e.g. Family, Adventure, Luxury" />
                <x-input-error :messages="$errors->get('package_type')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="duration" value="Duration" />
                <x-text-input id="duration" class="mt-1.5 block w-full" type="text" name="duration" :value="old('duration', $service?->duration)" placeholder="e.g. 7 Days / 6 Nights" />
                <x-input-error :messages="$errors->get('duration')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="hotel_requirement" value="Hotel Requirement" />
                <x-text-input id="hotel_requirement" class="mt-1.5 block w-full" type="text" name="hotel_requirement" :value="old('hotel_requirement', $service?->hotel_requirement)" placeholder="e.g. 4-star, Beachfront, City Center" />
                <x-input-error :messages="$errors->get('hotel_requirement')" class="mt-1" />
            </div>
            <div class="flex items-end">
                <label class="mb-1.5 inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="transport_requirement" value="1" @checked(old('transport_requirement', $service?->transport_requirement)) class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                    Transport Included
                </label>
            </div>
            <div class="sm:col-span-2">
                <x-input-label for="activities" value="Activities / Special Requirements" />
                <textarea id="activities" name="activities" rows="3" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Sightseeing, excursions, special arrangements...">{{ old('activities', $service?->activities) }}</textarea>
                <x-input-error :messages="$errors->get('activities')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Transportation -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm" x-show="serviceType === 'Transportation'" x-cloak>
        <h3 class="flex items-center gap-2 text-base font-semibold text-gray-900">
            <span class="flex h-7 w-7 items-center justify-center rounded-md bg-slate-200 text-slate-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6v6m8-6v6M7 12h10a2 2 0 012 2v6a1 1 0 01-1 1H6a1 1 0 01-1-1v-6a2 2 0 012-2zm0 0h10M4 16h16M8 20v-2m8 2v-2" /></svg>
            </span>
            Transportation Requirements
        </h3>
        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="pickup_location" value="Pickup Location" />
                <x-text-input id="pickup_location" class="mt-1.5 block w-full" type="text" name="pickup_location" :value="old('pickup_location', $service?->pickup_location)" placeholder="e.g. Airport, Home Address" />
                <x-input-error :messages="$errors->get('pickup_location')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="dropoff_location" value="Drop-off Location" />
                <x-text-input id="dropoff_location" class="mt-1.5 block w-full" type="text" name="dropoff_location" :value="old('dropoff_location', $service?->dropoff_location)" placeholder="e.g. Makkah Hotel, City Center" />
                <x-input-error :messages="$errors->get('dropoff_location')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="pickup_date" value="Pickup Date" />
                <x-text-input id="pickup_date" class="mt-1.5 block w-full" type="date" name="pickup_date" :value="old('pickup_date', $service?->pickup_date?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('pickup_date')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="pickup_time" value="Pickup Time" />
                <x-text-input id="pickup_time" class="mt-1.5 block w-full" type="time" name="pickup_time" :value="old('pickup_time', $service?->pickup_time)" />
                <x-input-error :messages="$errors->get('pickup_time')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="vehicle_type" value="Vehicle Type" />
                <x-text-input id="vehicle_type" class="mt-1.5 block w-full" type="text" name="vehicle_type" :value="old('vehicle_type', $service?->vehicle_type)" placeholder="e.g. Sedan, Hiace, Coaster, Bus" />
                <x-input-error :messages="$errors->get('vehicle_type')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="passengers" value="Number of Passengers" />
                <x-text-input id="passengers" class="mt-1.5 block w-full" type="number" name="passengers" min="1" max="999" :value="old('passengers', $service?->passengers)" />
                <x-input-error :messages="$errors->get('passengers')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Status -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Service Status</h3>
        <p class="mt-0.5 text-sm text-gray-500">Current progress of this service.</p>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($statuses as $status)
                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 px-4 py-3 transition hover:border-sky-300 hover:bg-sky-50/50">
                    <input type="radio" name="status" value="{{ $status }}" @checked(old('status', $service?->status ?? 'New') === $status) class="h-4 w-4 border-gray-300 text-sky-600 focus:ring-sky-500">
                    <span class="text-sm font-medium text-gray-700">{{ $status }}</span>
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('status')" class="mt-1" />
    </div>

    <!-- Actions -->
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ $back ?? route('services.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            {{ $submitLabel }}
        </button>
    </div>
</form>
