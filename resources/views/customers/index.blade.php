<x-admin-layout title="Customers">
    <!-- Page header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your travel agency customers.</p>
        </div>
        <a href="{{ route('customers.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Customer
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('customers.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search name, phone, email..." class="w-full rounded-lg border-gray-300 py-2.5 pl-10 pr-3 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
            </div>

            <select name="customer_source" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                <option value="">All Sources</option>
                <option value="direct" @selected(($filters['customer_source'] ?? '') === 'direct')>Direct</option>
                <option value="lead" @selected(($filters['customer_source'] ?? '') === 'lead')>Lead Conversion</option>
            </select>

            <select name="status" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                <option value="">All Statuses</option>
                <option value="Active" @selected(($filters['status'] ?? '') === 'Active')>Active</option>
                <option value="Inactive" @selected(($filters['status'] ?? '') === 'Inactive')>Inactive</option>
            </select>

            <select name="service" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                <option value="">All Services</option>
                @foreach ($services as $service)
                    <option value="{{ $service }}" @selected(($filters['service'] ?? '') === $service)>{{ $service }}</option>
                @endforeach
            </select>

            <div class="flex gap-2 sm:col-span-2 lg:col-span-4">
                <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if ($customers->total() > 0 && ($filters['search'] ?? $filters['customer_source'] ?? $filters['status'] ?? $filters['service'] ?? null))
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
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Service</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Customer Source</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Created Date</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($customers as $customer)
                        <tr class="transition hover:bg-gray-50">
                            <td class="max-w-[220px] px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <a href="{{ route('customers.show', $customer) }}" class="truncate font-medium text-gray-900 hover:text-sky-600">{{ $customer->name }}</a>
                                        @if ($customer->email)
                                            <p class="truncate text-xs text-gray-400">{{ $customer->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm text-gray-700">{{ $customer->phone }}</p>
                                @if ($customer->whatsapp && $customer->whatsapp !== $customer->phone)
                                    <p class="text-xs text-gray-400">WhatsApp: {{ $customer->whatsapp }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if ($customer->services_list)
                                    <div class="flex max-w-[190px] flex-wrap gap-1">
                                        @foreach ($customer->services_list as $svc)
                                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-sky-50 px-2 py-0.5 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-200">{{ $svc }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if ($customer->customer_source === 'lead')
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-200">Lead Conversion</span>
                                @else
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-200">Direct</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if ($customer->status === 'Active')
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">Active</span>
                                @else
                                    <span class="inline-flex items-center whitespace-nowrap rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-200">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-500">{{ $customer->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('customers.show', $customer) }}" title="View" class="rounded-lg p-2 text-gray-500 transition hover:bg-sky-50 hover:text-sky-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" title="Edit" class="rounded-lg p-2 text-gray-500 transition hover:bg-indigo-50 hover:text-indigo-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete" class="rounded-lg p-2 text-gray-500 transition hover:bg-rose-50 hover:text-rose-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </span>
                                <p class="mt-4 text-sm font-medium text-gray-500">No customers found</p>
                                <p class="mt-1 text-xs text-gray-400">Add your first customer or adjust the filters.</p>
                                <a href="{{ route('customers.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Customer
                                </a>
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
</x-admin-layout>
