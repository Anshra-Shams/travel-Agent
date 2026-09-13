<x-admin-layout title="Quotations & Bookings">
    <div class="space-y-6">
        <!-- Page header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quotations & Bookings</h1>
                <p class="mt-1 text-sm text-gray-500">Manage all customer quotations and bookings</p>
            </div>
            <a href="{{ route('quotations.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Booking
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
        @endif
        @if (session('info'))
            <div class="rounded-lg border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-700">{{ session('info') }}</div>
        @endif

        <!-- Toolbar -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('quotations.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <div class="relative flex-1">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by Customer Name, Booking ID or Quotation ID" class="w-full rounded-lg border-gray-300 py-2.5 pl-10 pr-3 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <select name="type" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">All Types</option>
                        <option value="booking" @selected(($filters['type'] ?? '') === 'booking')>Booking</option>
                        <option value="quotation" @selected(($filters['type'] ?? '') === 'quotation')>Quotation</option>
                    </select>

                    <select name="status" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">All Status</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ $s }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    @if ($quotations->total() > 0 && ($filters['search'] ?? $filters['type'] ?? $filters['status'] ?? null))
                        <a href="{{ route('quotations.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Combined table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Reference ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Services</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Type</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Total Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Payment Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Advance</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Created Date</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($quotations as $quotation)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <a href="{{ route('quotations.show', $quotation) }}" class="text-sm font-semibold text-sky-600 hover:text-sky-700">{{ $quotation->quotation_number }}</a>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full {{ $quotation->is_booking ? 'bg-sky-100 text-sky-700' : 'bg-violet-100 text-violet-700' }} text-xs font-bold">
                                            {{ strtoupper(substr($quotation->customer->name, 0, 1)) }}
                                        </span>
                                        <div class="min-w-0">
                                            <a href="{{ route('customers.show', $quotation->customer) }}" class="truncate text-sm font-medium text-gray-900 hover:text-sky-600">{{ $quotation->customer->name }}</a>
                                            @if ($quotation->customer->phone)
                                                <p class="truncate text-xs text-gray-400">{{ $quotation->customer->phone }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    @php $svcs = $quotation->services_list; @endphp
                                    <div class="flex flex-wrap gap-1">
                                        @foreach (array_slice($svcs, 0, 2) as $svc)
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600">{{ $svc }}</span>
                                        @endforeach
                                        @if (count($svcs) > 2)
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-500">+{{ count($svcs) - 2 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if ($quotation->is_booking)
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-200">
                                            <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                            Booking
                                        </span>
                                    @else
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-violet-50 px-2.5 py-0.5 text-xs font-medium text-violet-700 ring-1 ring-inset ring-violet-200">
                                            <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            Quotation
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-semibold text-gray-900">Rs. {{ number_format((float) $quotation->grand_total, 0) }}</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if ($quotation->is_booking)
                                        <span class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $quotation->payment_status_color }}">{{ $quotation->payment_status ?: '—' }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    @if ($quotation->is_booking)
                                        <span class="text-sm font-semibold text-gray-900">Rs. {{ number_format((float) $quotation->deposit_required, 0) }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500">{{ $quotation->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end" x-data="{ open: false }">
                                        <div class="relative">
                                            <button type="button" @click="open = !open" class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
<div x-show="open" x-transition @click.away="open = false" @keydown.escape.window="open = false" class="absolute right-0 z-20 mt-1 w-48 origin-top-right rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                                                <a href="{{ route('quotations.show', $quotation) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50 hover:text-sky-600">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                    View Invoice
                                                </a>
                                                <a href="{{ route('quotations.edit', $quotation) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50 hover:text-indigo-600">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    Edit
                                                </a>
                                                @if (!$quotation->is_booking)
                                                    <form method="POST" action="{{ route('quotations.convert', $quotation) }}" x-data="{ submitting: false }" @submit="submitting = true">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" :disabled="submitting" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50 hover:text-emerald-600 disabled:opacity-50">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                                            <span x-text="submitting ? 'Converting...' : 'Convert to Booking'">Convert to Booking</span>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('quotations.destroy', $quotation) }}" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50 hover:text-rose-600">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-16 text-center">
                                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                                        <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </span>
                                    <p class="mt-4 text-sm font-medium text-gray-500">No records found</p>
                                    <p class="mt-1 text-xs text-gray-400">Create your first booking or quotation to get started.</p>
                                    <a href="{{ route('quotations.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                        Add Booking
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($quotations->hasPages() || $quotations->total() > 0)
                <div class="border-t border-gray-200 px-4 py-3">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-gray-500">
                            Showing <span class="font-medium text-gray-700">{{ $quotations->firstItem() ?: 0 }}</span> to <span class="font-medium text-gray-700">{{ $quotations->lastItem() ?: 0 }}</span> of <span class="font-medium text-gray-700">{{ $quotations->total() }}</span> records
                        </p>
                        {{ $quotations->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>