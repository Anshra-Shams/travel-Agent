@php
    $lead = $lead ?? null;
@endphp

<form method="POST" action="{{ $action }}" class="space-y-3">
    @csrf
    @if ($method ?? null)
        @method($method)
    @endif

    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <h3 class="text-sm font-semibold text-gray-900">Customer Information</h3>

        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
                <x-input-label for="full_name" value="Customer Name *" />
                <x-text-input id="full_name" class="mt-1 block w-full !py-1" type="text" name="full_name" :value="old('full_name', $lead?->full_name)" required autofocus placeholder="e.g. Ahmed Khan" />
                <x-input-error :messages="$errors->get('full_name')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="phone" value="Phone Number *" />
                <x-text-input id="phone" class="mt-1 block w-full !py-1" type="text" name="phone" :value="old('phone', $lead?->phone)" required placeholder="e.g. +92 300 1234567" />
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" class="mt-1 block w-full !py-1" type="email" name="email" :value="old('email', $lead?->email)" placeholder="lead@example.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="source" value="Lead Source *" />
                <x-text-input id="source" class="mt-1 block w-full !py-1" type="text" name="source" :value="old('source', $lead?->source)" placeholder="e.g. Facebook, Website, Referral" />
                <x-input-error :messages="$errors->get('source')" class="mt-1" />
            </div>
        </div>

        <hr class="my-3 border-gray-100">

        <h3 class="text-sm font-semibold text-gray-900">Lead Details</h3>

        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
                <x-input-label for="service" value="Interested Service *" />
                <select id="service" name="service[]" multiple required class="mt-1 block w-full rounded-lg border-gray-300 !py-1 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    @foreach ($services as $service)
                        <option value="{{ $service }}" @selected(in_array($service, old('service', $lead?->services_list ?? []), true))>{{ $service }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('service')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="status" value="Status *" />
                <select id="status" name="status" required class="mt-1 block w-full rounded-lg border-gray-300 !py-1 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(old('status', $lead?->status ?? 'New') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="follow_up_date" value="Next Follow-up Date" />
                <x-text-input id="follow_up_date" class="mt-1 block w-full !py-1" type="date" name="follow_up_date" :value="old('follow_up_date', $lead?->follow_up_date?->format('Y-m-d'))" />
                <x-input-error :messages="$errors->get('follow_up_date')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="follow_up_time" value="Next Follow-up Time" />
                <x-text-input id="follow_up_time" class="mt-1 block w-full !py-1" type="time" name="follow_up_time" :value="old('follow_up_time', $lead?->follow_up_time ? \Carbon\Carbon::parse($lead->follow_up_time)->format('H:i') : '')" />
                <x-input-error :messages="$errors->get('follow_up_time')" class="mt-1" />
            </div>
        </div>

        <hr class="my-3 border-gray-100">

        <x-input-label for="notes" value="Requirements / Notes" />
        <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Write any additional requirements or notes here...">{{ old('notes', $lead?->notes) }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
    </div>

    <!-- Actions -->
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ $back ?? route('leads.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            {{ $submitLabel }}
        </button>
    </div>
</form>