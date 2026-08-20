<x-admin-layout :title="'Service: ' . $service->service_type">
    @php
        $typeColors = [
            'Flight Ticket' => 'bg-sky-50 text-sky-700 ring-sky-200',
            'Visa' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
            'Hotel' => 'bg-rose-50 text-rose-700 ring-rose-200',
            'Umrah' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'Hajj' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'Worldwide Tour Package' => 'bg-cyan-50 text-cyan-700 ring-cyan-200',
            'Transportation' => 'bg-slate-100 text-slate-700 ring-slate-200',
        ];
        $statusColors = [
            'New' => 'bg-sky-50 text-sky-700 ring-sky-200',
            'Requirements Pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'Ready for Quotation' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
            'Processing' => 'bg-violet-50 text-violet-700 ring-violet-200',
            'Completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'Cancelled' => 'bg-rose-50 text-rose-700 ring-rose-200',
        ];

        $specific = collect();
        if ($service->service_type === 'Flight Ticket') {
            $specific = collect([
                ['Departure', $service->departure],
                ['Arrival', $service->arrival],
                ['Departure Date', $service->departure_date?->format('d M Y')],
                ['Return Date', $service->return_date?->format('d M Y')],
                ['Trip Type', $service->trip_type],
                ['Passenger Count', $service->passenger_count],
                ['Preferred Airline', $service->preferred_airline],
                ['Class', $service->flight_class],
            ]);
        } elseif ($service->service_type === 'Visa') {
            $specific = collect([
                ['Country', $service->visa_country],
                ['Visa Type', $service->visa_type],
                ['Number of Applicants', $service->applicants],
                ['Visa Requirements', $service->visa_requirements],
            ]);
        } elseif ($service->service_type === 'Hotel') {
            $specific = collect([
                ['Hotel Preference', $service->hotel_preference],
                ['Check-in', $service->check_in?->format('d M Y')],
                ['Check-out', $service->check_out?->format('d M Y')],
                ['Rooms', $service->rooms],
                ['Adults', $service->adults],
                ['Children', $service->children],
                ['Room Type', $service->room_type],
            ]);
        } elseif (in_array($service->service_type, ['Umrah', 'Hajj'], true)) {
            $specific = collect([
                ['Package Type', $service->package_type],
                ['Makkah Hotel', $service->makkah_hotel],
                ['Madinah Hotel', $service->madinah_hotel],
                ['Nights in Makkah', $service->makkah_nights],
                ['Nights in Madinah', $service->madinah_nights],
            ]);
            if ($service->transport_requirement || $service->visa_requirement || $service->ticket_requirement) {
                $included = collect();
                if ($service->transport_requirement) $included->push('Transport');
                if ($service->visa_requirement) $included->push('Visa');
                if ($service->ticket_requirement) $included->push('Ticket');
                $specific->push(['Included in Package', $included->implode(', ')]);
            }
        } elseif ($service->service_type === 'Worldwide Tour Package') {
            $specific = collect([
                ['Package Type', $service->package_type],
                ['Duration', $service->duration],
                ['Hotel Requirement', $service->hotel_requirement],
                ['Transport Included', $service->transport_requirement ? 'Yes' : 'No'],
                ['Activities / Special Requirements', $service->activities],
            ]);
        } elseif ($service->service_type === 'Transportation') {
            $specific = collect([
                ['Pickup Location', $service->pickup_location],
                ['Drop-off Location', $service->dropoff_location],
                ['Pickup Date', $service->pickup_date?->format('d M Y')],
                ['Pickup Time', $service->pickup_time],
                ['Vehicle Type', $service->vehicle_type],
                ['Number of Passengers', $service->passengers],
            ]);
        }
        $specific = $specific->filter(fn ($row) => filled($row[1]))->values();
    @endphp

    <!-- Page header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Service Details</h1>
            <p class="mt-1 text-sm text-gray-500">Requirements recorded for this service.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Services
            </a>
            @if ($service->status === 'Ready for Quotation')
                <a href="{{ route('quotations.create', ['requirement' => $service->id]) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Create Quotation
                </a>
            @endif
            <a href="{{ route('services.edit', $service) }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Service
            </a>
        </div>
    </div>

    <!-- Service summary card -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-indigo-600 text-lg font-bold text-white">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-lg font-bold text-gray-900">{{ $service->service_type }}</h2>
                        <span class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $typeColors[$service->service_type] ?? 'bg-gray-50 text-gray-700 ring-gray-200' }}">
                            {{ $service->service_type }}
                        </span>
                        <span class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $statusColors[$service->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200' }}">
                            {{ $service->status }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">For <a href="{{ route('customers.show', $service->customer) }}" class="font-medium text-sky-600 hover:text-sky-700">{{ $service->customer->name }}</a>@if ($service->destination) · {{ $service->destination }}@endif</p>
                </div>
            </div>
            <form method="POST" action="{{ route('services.destroy', $service) }}" onsubmit="return confirm('Are you sure you want to delete this service?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Left column -->
        <div class="space-y-6 lg:col-span-2">
            <!-- General information -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">General Information</h3>
                <dl class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Customer</dt>
                        <dd class="mt-1">
                            <a href="{{ route('customers.show', $service->customer) }}" class="text-sm font-medium text-sky-600 hover:text-sky-700">{{ $service->customer->name }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Service Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $service->service_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Destination</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $service->destination ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Travel Date</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $service->travel_date ? $service->travel_date->format('d M Y') : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Number of Travelers</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $service->travelers ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Service Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $statusColors[$service->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200' }}">
                                {{ $service->status }}
                            </span>
                        </dd>
                    </div>
                </dl>

                @if ($service->requirements)
                    <div class="mt-5 border-t border-gray-100 pt-5">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Requirements / Notes</dt>
                        <dd class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-700">{{ $service->requirements }}</dd>
                    </div>
                @endif
            </div>

            <!-- Service-specific requirements -->
            @if ($specific->isNotEmpty())
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-gray-900">{{ $service->service_type }} Requirements</h3>
                    <dl class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                        @foreach ($specific as [$label, $value])
                            <div @if (strlen((string) $value) > 60) class="sm:col-span-2" @endif>
                                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $label }}</dt>
                                <dd class="mt-1 whitespace-pre-line text-sm text-gray-700">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            @endif
        </div>

        <!-- Right column -->
        <div class="space-y-6">
            <!-- Customer card -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">Customer</h3>
                <div class="mt-4 flex items-center gap-3">
                    <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-base font-bold text-emerald-700">
                        {{ strtoupper(substr($service->customer->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0">
                        <a href="{{ route('customers.show', $service->customer) }}" class="truncate font-medium text-gray-900 hover:text-sky-600">{{ $service->customer->name }}</a>
                        <p class="truncate text-xs text-gray-400">{{ $service->customer->phone }}</p>
                        @if ($service->customer->email)
                            <p class="truncate text-xs text-gray-400">{{ $service->customer->email }}</p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('customers.show', $service->customer) }}" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                    View Customer Profile
                </a>
            </div>

            <!-- Agent card -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">Managed By</h3>
                <div class="mt-4 flex items-center gap-3">
                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-sky-100 text-sm font-bold text-sky-700">
                        {{ $service->agent ? strtoupper(substr($service->agent->name, 0, 1)) : '—' }}
                    </span>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $service->agent?->name ?: 'Unassigned' }}</p>
                        <p class="text-xs text-gray-400">{{ $service->agent?->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Dates card -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">Dates</h3>
                <dl class="mt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Created</dt>
                        <dd class="text-sm text-gray-700">{{ $service->created_at->format('d M Y') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Last Updated</dt>
                        <dd class="text-sm text-gray-700">{{ $service->updated_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-admin-layout>
