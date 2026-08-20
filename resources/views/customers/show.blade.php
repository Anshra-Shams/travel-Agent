<x-admin-layout :title="'Customer: ' . $customer->name">
    <!-- Page header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customer Profile</h1>
            <p class="mt-1 text-sm text-gray-500">Complete customer information and activity.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('customers.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Customers
            </a>
            <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Customer
            </a>
        </div>
    </div>

    <!-- Customer summary card -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-lg font-bold text-white">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </span>
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-lg font-bold text-gray-900">{{ $customer->name }}</h2>
                        @if ($customer->status === 'Active')
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">Active</span>
                        @else
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-200">Inactive</span>
                        @endif
                        @if ($customer->customer_source === 'lead')
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-200">Lead Conversion</span>
                        @else
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-200">Direct</span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-gray-500">{{ $customer->phone }}@if ($customer->email) · {{ $customer->email }} @endif</p>
                    @if ($customer->services_list)
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach ($customer->services_list as $svc)
                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-200">{{ $svc }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Are you sure you want to delete this customer?');">
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
            <!-- Personal information -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">Personal Information</h3>
                <dl class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Full Name</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $customer->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">WhatsApp</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->whatsapp ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Email</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->email ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->gender ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Date of Birth</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->date_of_birth ? $customer->date_of_birth->format('d M Y') : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Address</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->address ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Country</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->country ?: '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Created Date</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->created_at->format('d M Y, h:i A') }}</dd>
                    </div>
                </dl>

                @if ($customer->notes)
                    <div class="mt-5 border-t border-gray-100 pt-5">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Notes</dt>
                        <dd class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-700">{{ $customer->notes }}</dd>
                    </div>
                @endif
            </div>

            <!-- Passport / CNIC information -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">Passport / CNIC Information</h3>
                <dl class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">CNIC</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->cnic ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Passport Number</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->passport_number ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Passport Expiry</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->passport_expiry ? $customer->passport_expiry->format('d M Y') : '—' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Services -->
            @php
                $serviceTypeColors = [
                    'Flight Ticket' => 'bg-sky-50 text-sky-700 ring-sky-200',
                    'Visa' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
                    'Hotel' => 'bg-rose-50 text-rose-700 ring-rose-200',
                    'Umrah' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                    'Hajj' => 'bg-amber-50 text-amber-700 ring-amber-200',
                    'Worldwide Tour Package' => 'bg-cyan-50 text-cyan-700 ring-cyan-200',
                    'Transportation' => 'bg-slate-100 text-slate-700 ring-slate-200',
                ];
                $serviceStatusColors = [
                    'New' => 'bg-sky-50 text-sky-700 ring-sky-200',
                    'Requirements Pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
                    'Ready for Quotation' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
                    'Processing' => 'bg-violet-50 text-violet-700 ring-violet-200',
                    'Completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                    'Cancelled' => 'bg-rose-50 text-rose-700 ring-rose-200',
                ];
            @endphp
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-6 py-4">
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-semibold text-gray-900">Services</h3>
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">{{ $customer->services->count() }}</span>
                    </div>
                    <a href="{{ route('services.create', ['customer' => $customer->id]) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-sky-700">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Service
                    </a>
                </div>

                @forelse ($customer->services as $service)
                    <div class="border-b border-gray-50 px-6 py-4 last:border-b-0">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a href="{{ route('services.show', $service) }}" class="font-medium text-gray-900 hover:text-sky-600">{{ $service->service_type }}</a>
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $serviceStatusColors[$service->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200' }}">{{ $service->status }}</span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400">
                                    @if ($service->destination){{ $service->destination }} · @endif
                                    @if ($service->travel_date){{ $service->travel_date->format('d M Y') }} · @endif
                                    @if ($service->travelers){{ $service->travelers }} traveler(s)@endif
                                    @if (!$service->destination && !$service->travel_date && !$service->travelers)—@endif
                                </p>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('services.show', $service) }}" title="View" class="rounded-lg p-2 text-gray-500 transition hover:bg-sky-50 hover:text-sky-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('services.edit', $service) }}" title="Edit" class="rounded-lg p-2 text-gray-500 transition hover:bg-indigo-50 hover:text-indigo-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('services.destroy', $service) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete" class="rounded-lg p-2 text-gray-500 transition hover:bg-rose-50 hover:text-rose-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <p class="mt-3 text-sm font-medium text-gray-500">No services recorded yet</p>
                        <p class="mt-1 text-xs text-gray-400">Add a service to start recording requirements.</p>
                        <a href="{{ route('services.create', ['customer' => $customer->id]) }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-sky-700">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Add New Service
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Activity history -->
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Activity History</h3>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">{{ $customer->activities->count() }}</span>
                </div>
                @php
                    $activityStyles = [
                        'system' => 'bg-slate-100 text-slate-500',
                        'status' => 'bg-violet-100 text-violet-600',
                        'note' => 'bg-sky-100 text-sky-600',
                        'follow-up' => 'bg-amber-100 text-amber-600',
                        'conversion' => 'bg-indigo-100 text-indigo-600',
                        'call' => 'bg-rose-100 text-rose-600',
                    ];
                @endphp
                @forelse ($customer->activities as $activity)
                    <div class="border-b border-gray-50 px-6 py-4 last:border-b-0">
                        <div class="flex gap-3">
                            <span class="mt-1 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full {{ $activityStyles[$activity->type] ?? 'bg-gray-100 text-gray-500' }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm text-gray-800">{{ $activity->description }}</p>
                                <p class="mt-1 text-xs text-gray-400">{{ $activity->agent?->name ?: 'System' }} · {{ $activity->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm font-medium text-gray-500">No activity recorded yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right column -->
        <div class="space-y-6">
            <!-- Customer source -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">Customer Source</h3>
                <div class="mt-4 flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                    @if ($customer->customer_source === 'lead')
                        <span class="inline-flex items-center gap-2 text-sm font-medium text-indigo-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                            Lead Conversion
                        </span>
                        <a href="{{ route('leads.show', $customer->lead) }}" class="text-sm font-medium text-sky-600 transition hover:text-sky-700">View Lead</a>
                    @else
                        <span class="inline-flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0l3 3m-3-3l-3 3m6-11a4 4 0 11-8 0 4 4 0 018 0zM5 21a7 7 0 0114 0" />
                            </svg>
                            Direct
                        </span>
                        <span class="text-xs text-gray-400">Created directly</span>
                    @endif
                </div>
            </div>

            <!-- Add follow-up -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">Add Follow-up</h3>
                <form method="POST" action="{{ route('customers.follow-ups.store', $customer) }}" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <x-input-label value="Follow-up Date" />
                        <x-text-input type="date" name="follow_up_date" class="mt-1.5 block w-full" :value="old('follow_up_date', now()->addDays(1)->format('Y-m-d'))" />
                        <x-input-error :messages="$errors->get('follow_up_date')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label value="Note" />
                        <textarea name="note" rows="3" required class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" placeholder="What was discussed..."></textarea>
                        <x-input-error :messages="$errors->get('note')" class="mt-1" />
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600">
                        Add Follow-up
                    </button>
                </form>
            </div>

            <!-- Follow-up history -->
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Follow-ups</h3>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">{{ $customer->followUps->count() }}</span>
                </div>
                @forelse ($customer->followUps as $followUp)
                    <div class="border-b border-gray-50 px-6 py-4 last:border-b-0">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-900">
                                <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $followUp->follow_up_date ? $followUp->follow_up_date->format('d M Y') : 'No date' }}
                            </span>
                            @if ($followUp->completed_at)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">Completed</span>
                            @endif
                        </div>
                        @if ($followUp->note)
                            <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $followUp->note }}</p>
                        @endif
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-xs text-gray-400">by {{ $followUp->agent?->name ?: '—' }} · {{ $followUp->created_at->format('d M Y') }}</span>
                            @if (!$followUp->completed_at)
                                <form method="POST" action="{{ route('customer-follow-ups.complete', $followUp) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-md border border-gray-200 px-2 py-1 text-xs font-medium text-gray-600 transition hover:bg-emerald-50 hover:text-emerald-600">Mark Complete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm font-medium text-gray-500">No follow-ups yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quotations -->
    @php
        $quotationStatusColors = [
            'Draft' => 'bg-gray-50 text-gray-700 ring-gray-200',
            'Sent' => 'bg-sky-50 text-sky-700 ring-sky-200',
            'Accepted' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'Rejected' => 'bg-rose-50 text-rose-700 ring-rose-200',
            'Expired' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'Cancelled' => 'bg-slate-100 text-slate-600 ring-slate-200',
        ];
    @endphp
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-6 py-4">
            <div class="flex items-center gap-2">
                <h3 class="text-base font-semibold text-gray-900">Quotations</h3>
                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">{{ $customer->quotations->count() }}</span>
            </div>
            @if ($customer->services->count() > 0)
                <a href="{{ route('quotations.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-sky-700">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Create Quotation
                </a>
            @endif
        </div>
        @forelse ($customer->quotations as $quotation)
            <div class="border-b border-gray-50 px-6 py-4 last:border-b-0">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('quotations.show', $quotation) }}" class="font-medium text-gray-900 hover:text-sky-600">{{ $quotation->quotation_number }}</a>
                            <span class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $quotationStatusColors[$quotation->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200' }}">{{ $quotation->status }}</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-400">
                            {{ $quotation->service_type }} · Rs. {{ number_format($quotation->grand_total, 0) }} · {{ $quotation->quotation_date->format('d M Y') }}
                        </p>
                    </div>
                    <a href="{{ route('quotations.show', $quotation) }}" title="View" class="rounded-lg p-2 text-gray-500 transition hover:bg-sky-50 hover:text-sky-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="px-6 py-10 text-center">
                <p class="text-sm font-medium text-gray-500">No quotations yet</p>
                <p class="mt-1 text-xs text-gray-400">Create a quotation from a service requirement.</p>
            </div>
        @endforelse
    </div>

    <!-- Connected modules (scalable placeholders) -->
    <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @php
            $modules = [
                ['name' => 'Bookings', 'route' => 'bookings.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'chip' => 'bg-indigo-50 text-indigo-600'],
                ['name' => 'Payments', 'route' => 'payments.index', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'chip' => 'bg-amber-50 text-amber-600'],
                ['name' => 'Documents', 'route' => 'documents.index', 'icon' => 'M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'chip' => 'bg-rose-50 text-rose-600'],
            ];
        @endphp
        @foreach ($modules as $module)
            <div class="flex flex-col rounded-xl border border-dashed border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg {{ $module['chip'] }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{!! $module['icon'] !!}" />
                        </svg>
                    </span>
                    <h3 class="font-semibold text-gray-900">{{ $module['name'] }}</h3>
                </div>
                <p class="mt-2 text-xs text-gray-400">No records yet. This module will be connected soon.</p>
            </div>
        @endforeach
    </div>
</x-admin-layout>
