<x-admin-layout :title="$serviceType . ' — Customers'">
    @php
        $statusColors = [
            'New' => 'bg-sky-50 text-sky-700 ring-sky-200',
            'Requirements Pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
            'Ready for Quotation' => 'bg-indigo-50 text-indigo-700 ring-indigo-200',
            'Processing' => 'bg-violet-50 text-violet-700 ring-violet-200',
            'Completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
            'Cancelled' => 'bg-rose-50 text-rose-700 ring-rose-200',
        ];
    @endphp

    <!-- Page header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl {{ $colors['icon_bg'] }} text-xl">{{ $serviceTypeModel->icon }}</span>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $serviceType }}</h1>
                    <p class="mt-0.5 text-sm text-gray-500">{{ $serviceTypeModel->description }}</p>
                </div>
            </div>
        </div>
        <a href="{{ route('services.create', ['service' => $serviceType]) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add {{ $serviceType }}
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('services.type', $serviceType) }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <div class="relative sm:col-span-2 lg:col-span-1">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search name or phone..." class="w-full rounded-lg border-gray-300 py-2.5 pl-10 pr-3 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
            </div>

            <select name="status" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                <option value="">All Statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                @endforeach
            </select>

            <input type="date" name="travel_date_from" value="{{ $filters['travel_date_from'] ?? '' }}" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500" title="Travel date from">

            <input type="date" name="travel_date_to" value="{{ $filters['travel_date_to'] ?? '' }}" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500" title="Travel date to">

            <div class="flex gap-2 sm:col-span-2 lg:col-span-1">
                <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if ($customers->total() > 0 && ($filters['search'] ?? $filters['status'] ?? $filters['travel_date_from'] ?? $filters['travel_date_to'] ?? null))
                    <a href="{{ route('services.type', $serviceType) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Customers table — Desktop -->
    <div class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm md:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Travel Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Travelers</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Created</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($customers as $customer)
                        @php $req = $customer->services->first(); @endphp
                        <tr class="transition hover:bg-gray-50">
                            <td class="max-w-[200px] px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </span>
                                    <a href="{{ route('customers.show', $customer) }}" class="truncate text-sm font-medium text-gray-900 hover:text-sky-600">{{ $customer->name }}</a>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $customer->phone }}</td>
                            <td class="px-4 py-4">
                                @if ($req)
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $statusColors[$req->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200' }}">
                                        {{ $req->status }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-500 ring-1 ring-inset ring-gray-200">
                                        No requirements
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500">{{ $req && $req->travel_date ? $req->travel_date->format('d M Y') : '—' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-700">{{ $req && $req->travelers ? $req->travelers : '—' }}</td>
                            <td class="px-4 py-4 text-sm text-gray-500">{{ $customer->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    @if ($req)
                                        @if ($req->status === 'Ready for Quotation')
                                            <a href="{{ route('quotations.create', ['requirement' => $req->id]) }}" title="Create Quotation" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-50">
                                                Quotation
                                            </a>
                                        @endif
                                        <a href="{{ route('services.show', $req) }}" title="View" class="rounded-lg p-2 text-gray-500 transition hover:bg-sky-50 hover:text-sky-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <a href="{{ route('services.edit', $req) }}" title="Edit" class="rounded-lg p-2 text-gray-500 transition hover:bg-indigo-50 hover:text-indigo-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                    @else
                                        <a href="{{ route('services.create', ['service' => $serviceType, 'customer' => $customer->id]) }}" title="Add Requirement" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-sky-600 transition hover:bg-sky-50">
                                            + Add
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-3xl">{{ $serviceTypeModel->icon }}</span>
                                <p class="mt-4 text-sm font-medium text-gray-500">No customers found</p>
                                <p class="mt-1 text-xs text-gray-400">Customers who select "{{ $serviceType }}" will appear here.</p>
                                <a href="{{ route('services.create', ['service' => $serviceType]) }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                    Add {{ $serviceType }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Customers cards — Mobile -->
    <div class="space-y-3 md:hidden">
        @forelse ($customers as $customer)
            @php $req = $customer->services->first(); @endphp
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </span>
                        <div>
                            <a href="{{ route('customers.show', $customer) }}" class="text-sm font-semibold text-gray-900 hover:text-sky-600">{{ $customer->name }}</a>
                            <p class="text-xs text-gray-500">{{ $customer->phone }}</p>
                        </div>
                    </div>
                    @if ($req)
                        <span class="inline-flex items-center whitespace-nowrap rounded-full px-2 py-0.5 text-[11px] font-medium ring-1 ring-inset {{ $statusColors[$req->status] ?? 'bg-gray-50 text-gray-700 ring-gray-200' }}">
                            {{ $req->status }}
                        </span>
                    @else
                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-gray-50 px-2 py-0.5 text-[11px] font-medium text-gray-500 ring-1 ring-inset ring-gray-200">
                            No requirements
                        </span>
                    @endif
                </div>
                <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-gray-500">
                    <div>
                        <span class="font-medium text-gray-600">Travel:</span>
                        {{ $req && $req->travel_date ? $req->travel_date->format('d M Y') : '—' }}
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Travelers:</span>
                        {{ $req && $req->travelers ? $req->travelers : '—' }}
                    </div>
                </div>
                <div class="mt-3 flex gap-2 border-t border-gray-100 pt-3">
                    @if ($req)
                        @if ($req->status === 'Ready for Quotation')
                            <a href="{{ route('quotations.create', ['requirement' => $req->id]) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100">Quotation</a>
                        @endif
                        <a href="{{ route('services.show', $req) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">View</a>
                        <a href="{{ route('services.edit', $req) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">Edit</a>
                    @else
                        <a href="{{ route('services.create', ['service' => $serviceType, 'customer' => $customer->id]) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 transition hover:bg-sky-100">+ Add Requirement</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-gray-200 bg-white py-12 text-center shadow-sm">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-3xl">{{ $serviceTypeModel->icon }}</span>
                <p class="mt-4 text-sm font-medium text-gray-500">No customers found</p>
                <p class="mt-1 text-xs text-gray-400">Customers who select "{{ $serviceType }}" will appear here.</p>
                <a href="{{ route('services.create', ['service' => $serviceType]) }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Add {{ $serviceType }}
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($customers->hasPages())
        <div class="mt-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium text-gray-700">{{ $customers->firstItem() }}</span> to <span class="font-medium text-gray-700">{{ $customers->lastItem() }}</span> of <span class="font-medium text-gray-700">{{ $customers->total() }}</span> customers
                </p>
                {{ $customers->links() }}
            </div>
        </div>
    @endif
</x-admin-layout>
