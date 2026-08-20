<x-admin-layout title="Quotations">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quotations</h1>
            <p class="mt-1 text-sm text-gray-500">Create and manage customer service quotations.</p>
        </div>
        <a href="{{ route('quotations.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Create Quotation
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('quotations.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-6">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">Search Customer</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name or phone..." class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">Service Type</label>
                <select name="service_type" class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    <option value="">All Services</option>
                    @foreach ($serviceTypes as $st)
                        <option value="{{ $st }}" @selected(($filters['service_type'] ?? '') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">Status</label>
                <select name="status" class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">Date From</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-gray-400">Date To</label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="mt-1.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">Filter</button>
                <a href="{{ route('quotations.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">Clear</a>
            </div>
        </form>
    </div>

    <!-- Desktop Table -->
    <div class="hidden overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm md:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Quotation #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Destination</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Valid Until</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($quotations as $quotation)
                        <tr class="transition hover:bg-gray-50">
                            <td class="whitespace-nowrap px-6 py-4">
                                <a href="{{ route('quotations.show', $quotation) }}" class="text-sm font-semibold text-sky-600 hover:text-sky-700">{{ $quotation->quotation_number }}</a>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $quotation->customer->name }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">{{ $quotation->service_type }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $quotation->destination ?: '—' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-gray-900">Rs. {{ number_format($quotation->grand_total, 0) }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $quotation->quotation_date->format('d M Y') }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $quotation->valid_until->format('d M Y') }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $quotation->status_color }}">{{ $quotation->status }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('quotations.show', $quotation) }}" title="View" class="rounded-lg p-2 text-gray-500 transition hover:bg-sky-50 hover:text-sky-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    @if ($quotation->status === 'Draft')
                                        <a href="{{ route('quotations.edit', $quotation) }}" title="Edit" class="rounded-lg p-2 text-gray-500 transition hover:bg-indigo-50 hover:text-indigo-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form method="POST" action="{{ route('quotations.status', $quotation) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="Sent">
                                            <button type="submit" title="Send" class="rounded-lg p-2 text-gray-500 transition hover:bg-emerald-50 hover:text-emerald-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('quotations.destroy', $quotation) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Delete" class="rounded-lg p-2 text-gray-500 transition hover:bg-rose-50 hover:text-rose-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="px-6 py-12 text-center">
                            <p class="text-sm font-medium text-gray-500">No quotations found.</p>
                            <a href="{{ route('quotations.create') }}" class="mt-3 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">Create your first quotation</a>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($quotations->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $quotations->links() }}</div>
        @endif
    </div>

    <!-- Mobile Cards -->
    <div class="space-y-3 md:hidden">
        @forelse ($quotations as $quotation)
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <a href="{{ route('quotations.show', $quotation) }}" class="text-sm font-bold text-sky-600">{{ $quotation->quotation_number }}</a>
                        <p class="mt-0.5 text-sm font-medium text-gray-900">{{ $quotation->customer->name }}</p>
                    </div>
                    <span class="inline-flex items-center whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset {{ $quotation->status_color }}">{{ $quotation->status }}</span>
                </div>
                <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-gray-500">
                    <div><span class="font-medium text-gray-700">Service:</span> {{ $quotation->service_type }}</div>
                    <div><span class="font-medium text-gray-700">Total:</span> Rs. {{ number_format($quotation->grand_total, 0) }}</div>
                    <div><span class="font-medium text-gray-700">Date:</span> {{ $quotation->quotation_date->format('d M Y') }}</div>
                    <div><span class="font-medium text-gray-700">Valid:</span> {{ $quotation->valid_until->format('d M Y') }}</div>
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <a href="{{ route('quotations.show', $quotation) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-50">View</a>
                    @if ($quotation->status === 'Draft')
                        <a href="{{ route('quotations.edit', $quotation) }}" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-indigo-600 transition hover:bg-indigo-50">Edit</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-gray-200 bg-white p-8 text-center shadow-sm">
                <p class="text-sm font-medium text-gray-500">No quotations found.</p>
            </div>
        @endforelse
        @if ($quotations->hasPages())
            <div class="py-2">{{ $quotations->links() }}</div>
        @endif
    </div>
</x-admin-layout>
