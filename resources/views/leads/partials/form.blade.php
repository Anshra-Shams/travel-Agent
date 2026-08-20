@php
    $lead = $lead ?? null;
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method ?? null)
        @method($method)
    @endif

    <!-- Contact information -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Contact Information</h3>
        <p class="mt-0.5 text-sm text-gray-500">Basic details of the lead.</p>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="full_name" value="Full Name" />
                <x-text-input id="full_name" class="mt-1.5 block w-full" type="text" name="full_name" :value="old('full_name', $lead?->full_name)" required autofocus placeholder="e.g. Ahmed Khan" />
                <x-input-error :messages="$errors->get('full_name')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="phone" value="Phone" />
                <x-text-input id="phone" class="mt-1.5 block w-full" type="text" name="phone" :value="old('phone', $lead?->phone)" required placeholder="e.g. +92 300 1234567" />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1.5 block w-full" type="email" name="email" :value="old('email', $lead?->email)" placeholder="lead@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="whatsapp" value="WhatsApp Number" />
                <x-text-input id="whatsapp" class="mt-1.5 block w-full" type="text" name="whatsapp" :value="old('whatsapp', $lead?->whatsapp)" placeholder="e.g. +92 300 1234567" />
                <x-input-error :messages="$errors->get('whatsapp')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Travel requirements -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Travel Requirements</h3>
        <p class="mt-0.5 text-sm text-gray-500">What the lead is looking for.</p>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="service" value="Service Required" />
                <select id="service" name="service" required class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    <option value="">Select a service</option>
                    @foreach ($services as $service)
                        <option value="{{ $service }}" @selected(old('service', $lead?->service) === $service)>{{ $service }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('service')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="destination" value="Travel Destination" />
                <x-text-input id="destination" class="mt-1.5 block w-full" type="text" name="destination" :value="old('destination', $lead?->destination)" placeholder="e.g. Makkah, Istanbul, Dubai" />
                <x-input-error :messages="$errors->get('destination')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="travel_date" value="Travel Date" />
                <x-text-input id="travel_date" class="mt-1.5 block w-full" type="date" name="travel_date" :value="old('travel_date', $lead?->travel_date?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('travel_date')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="travelers" value="Number of Travelers" />
                <x-text-input id="travelers" class="mt-1.5 block w-full" type="number" name="travelers" min="1" max="999" :value="old('travelers', $lead?->travelers ?? 1)" />
                <x-input-error :messages="$errors->get('travelers')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Assignment & status -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Assignment & Status</h3>
        <p class="mt-0.5 text-sm text-gray-500">Assign the lead and set its current stage.</p>

        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="source" value="Lead Source" />
                <x-text-input id="source" class="mt-1.5 block w-full" type="text" name="source" :value="old('source', $lead?->source)" placeholder="e.g. Facebook, Website, Referral" />
                <x-input-error :messages="$errors->get('source')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="agent_id" value="Assigned Agent" />
                <select id="agent_id" name="agent_id" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    <option value="">Unassigned</option>
                    @foreach ($agents as $agent)
                        <option value="{{ $agent->id }}" @selected(old('agent_id', $lead?->agent_id) == $agent->id)>{{ $agent->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('agent_id')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="status" value="Lead Status" />
                <select id="status" name="status" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $lead?->status ?? 'New') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="follow_up_date" value="Follow-up Date" />
                <x-text-input id="follow_up_date" class="mt-1.5 block w-full" type="date" name="follow_up_date" :value="old('follow_up_date', $lead?->follow_up_date?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('follow_up_date')" class="mt-1" />
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <h3 class="text-base font-semibold text-gray-900">Notes</h3>
        <p class="mt-0.5 text-sm text-gray-500">Any additional information about the lead.</p>

        <div class="mt-5">
            <x-input-label for="notes" value="Notes" />
            <textarea id="notes" name="notes" rows="4" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Write any additional notes here...">{{ old('notes', $lead?->notes) }}</textarea>
            <x-input-error :messages="$errors->get('notes')" class="mt-1" />
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ $back ?? route('leads.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            {{ $submitLabel }}
        </button>
    </div>
</form>
