<x-admin-layout :title="'Customer: ' . $customer->name">
    <div x-data="{ tab: 'personal' }" class="space-y-6">
        <!-- Page header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Customer Profile</h1>
                <p class="mt-1 text-sm text-gray-500">View and manage customer records</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('customers.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Customers
                </a>
            </div>
        </div>

        <!-- Profile summary -->
        <div class="flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-lg font-bold text-white">
                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                </span>
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-lg font-bold text-gray-900">{{ $customer->name }}</h2>
                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200">ID: #{{ $customer->id }}</span>
                        @if ($customer->status === 'Active')
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">Active</span>
                        @else
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-200">Inactive</span>
                        @endif
                        @if ($customer->customer_source === 'lead')
                            <a href="{{ route('leads.show', $customer->lead) }}" class="inline-flex items-center gap-1 whitespace-nowrap rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-200 hover:bg-indigo-100">
                                Lead Conversion
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        @else
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-200">Direct</span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        <span class="font-medium text-gray-700">{{ $customer->phone }}</span>
                        @if ($customer->email) · {{ $customer->email }} @endif
                        @if ($customer->city) · {{ $customer->city }} @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex overflow-x-auto">
                    <button type="button" @click="tab = 'personal'" :class="tab === 'personal' ? 'border-sky-600 text-sky-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-4 py-3 text-sm font-semibold">
                        Personal Information
                    </button>
                    <button type="button" @click="tab = 'bookings'" :class="tab === 'bookings' ? 'border-sky-600 text-sky-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-4 py-3 text-sm font-semibold">
                        Bookings
                    </button>
                    <button type="button" @click="tab = 'payments'" :class="tab === 'payments' ? 'border-sky-600 text-sky-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-4 py-3 text-sm font-semibold">
                        Payments
                    </button>
                    <button type="button" @click="tab = 'documents'" :class="tab === 'documents' ? 'border-sky-600 text-sky-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'" class="whitespace-nowrap border-b-2 px-4 py-3 text-sm font-semibold">
                        Documents
                    </button>
                </nav>
            </div>

            <!-- Personal Information -->
            <div x-show="tab === 'personal'">
                <div class="p-5">
                    <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                        @php
                            $fields = [
                                ['label' => 'Full Name', 'value' => $customer->name],
                                ['label' => 'Phone Number', 'value' => $customer->phone],
                                ['label' => 'Email', 'value' => $customer->email ?: '—'],
                                ['label' => 'Customer Status', 'value' => $customer->status ?: '—'],
                            ];
                        @endphp
                        @foreach ($fields as $field)
                            <div>
                                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">{{ $field['label'] }}</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900">{{ $field['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    <div class="mt-5 border-t border-gray-100 pt-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Address</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->address ?: '—' }}</dd>
                    </div>

                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Services Interested</dt>
                        <dd class="mt-1">
                            <div class="flex flex-wrap gap-1">
                                @forelse ($customer->services_list as $svc)
                                    <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-200">{{ $svc }}</span>
                                @empty
                                    <span class="text-sm text-gray-400">—</span>
                                @endforelse
                            </div>
                        </dd>
                    </div>

                    @if ($customer->notes)
                        <div class="mt-4 border-t border-gray-100 pt-4">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Notes</dt>
                            <dd class="mt-1 whitespace-pre-line text-sm leading-relaxed text-gray-700">{{ $customer->notes }}</dd>
                        </div>
                    @endif

                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Created Date</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $customer->created_at->format('d M Y, h:i A') }}</dd>
                    </div>
                </div>
            </div>

            <!-- Bookings -->
            <div x-show="tab === 'bookings'" style="display: none">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Booking ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Service</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Booking Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Total Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Payment Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Booking Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <tr>
                                <td colspan="6" class="px-4 py-14 text-center">
                                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <p class="mt-4 text-sm font-medium text-gray-500">No bookings yet</p>
                                    <p class="mt-1 text-xs text-gray-400">Bookings will appear here once created.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payments -->
            <div x-show="tab === 'payments'" style="display: none">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Payment ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Booking ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Payment Method</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Payment Date</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <tr>
                                <td colspan="6" class="px-4 py-14 text-center">
                                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                    <p class="mt-4 text-sm font-medium text-gray-500">No payments yet</p>
                                    <p class="mt-1 text-xs text-gray-400">Payments will appear here once recorded.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Documents -->
            <div x-show="tab === 'documents'" style="display: none">
                @php
                    $docs = [
                        ['name' => 'Passport Copy', 'icon' => 'M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                        ['name' => 'CNIC Copy', 'icon' => 'M3 10h18M7 15h2m4 0h4M5 6h14a1 1 0 011 1v10a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z'],
                        ['name' => 'Photograph', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['name' => 'Visa Documents', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ];
                @endphp
                <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2">
                    @foreach ($docs as $doc)
                        <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white text-gray-500 shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $doc['icon'] }}" />
                                    </svg>
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $doc['name'] }}</p>
                                    <p class="text-xs text-gray-400">Not uploaded yet</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500 ring-1 ring-inset ring-gray-200">Pending</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Services -->
        @php
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
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
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
                <div class="border-b border-gray-50 px-5 py-3 last:border-b-0">
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
                <div class="px-5 py-8 text-center">
                    <p class="text-sm font-medium text-gray-500">No services recorded yet</p>
                    <a href="{{ route('services.create', ['customer' => $customer->id]) }}" class="mt-3 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-sky-700">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Service
                    </a>
                </div>
            @endforelse
        </div>

        </div>
</x-admin-layout>