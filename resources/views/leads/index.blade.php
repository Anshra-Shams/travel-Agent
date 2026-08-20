<x-admin-layout title="Leads">
    <!-- Page header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Leads</h1>
            <p class="mt-1 text-sm text-gray-500">Manage and track your travel leads.</p>
        </div>
        <a href="{{ route('leads.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Lead
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('leads.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search name, phone, email..." class="w-full rounded-lg border-gray-300 py-2.5 pl-10 pr-3 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
            </div>

            <select name="service" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                <option value="">All Services</option>
                @foreach ($services as $service)
                    <option value="{{ $service }}" @selected(($filters['service'] ?? '') === $service)>{{ $service }}</option>
                @endforeach
            </select>

            <select name="status" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                <option value="">All Statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ $status }}</option>
                @endforeach
            </select>

            <select name="agent" class="rounded-lg border-gray-300 py-2.5 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                <option value="">All Agents</option>
                @foreach ($agents as $agent)
                    <option value="{{ $agent->id }}" @selected(($filters['agent'] ?? '') === (string) $agent->id)>{{ $agent->name }}</option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                @if ($leads->total() > 0 && ($filters['search'] ?? $filters['service'] ?? $filters['status'] ?? $filters['agent'] ?? null))
                    <a href="{{ route('leads.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Leads table -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Lead Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Service</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Destination</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Assigned Agent</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Follow-up Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Created Date</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($leads as $lead)
                        <tr class="transition hover:bg-gray-50">
                            <td class="max-w-[220px] px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-sky-100 text-sm font-bold text-sky-700">
                                        {{ strtoupper(substr($lead->full_name, 0, 1)) }}
                                    </span>
                                    <div class="min-w-0">
                                        <a href="{{ route('leads.show', $lead) }}" class="truncate font-medium text-gray-900 hover:text-sky-600">{{ $lead->full_name }}</a>
                                        @if ($lead->email)
                                            <p class="truncate text-xs text-gray-400">{{ $lead->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-sm text-gray-700">{{ $lead->phone }}</p>
                                @if ($lead->whatsapp && $lead->whatsapp !== $lead->phone)
                                    <p class="text-xs text-gray-400">WhatsApp: {{ $lead->whatsapp }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-700">{{ $lead->service }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-700">{{ $lead->destination ?: '—' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-700">{{ $lead->assignedAgent?->name ?: '—' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <x-status-badge :status="$lead->status" />
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-700">{{ $lead->follow_up_date ? $lead->follow_up_date->format('d M Y') : '—' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-500">{{ $lead->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('leads.show', $lead) }}" title="View" class="rounded-lg p-2 text-gray-500 transition hover:bg-sky-50 hover:text-sky-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('leads.edit', $lead) }}" title="Edit" class="rounded-lg p-2 text-gray-500 transition hover:bg-indigo-50 hover:text-indigo-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('leads.convert', $lead) }}" class="inline" onsubmit="return confirm('Convert this lead to a customer?');">
                                        @csrf
                                        <button type="submit" title="Convert to Customer" class="rounded-lg p-2 text-gray-500 transition hover:bg-emerald-50 hover:text-emerald-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7zM16 11.2a4 4 0 110-5.2" />
                                            </svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('leads.destroy', $lead) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this lead?');">
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
                            <td colspan="9" class="px-4 py-16 text-center">
                                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </span>
                                <p class="mt-4 text-sm font-medium text-gray-500">No leads found</p>
                                <p class="mt-1 text-xs text-gray-400">Add your first lead or adjust the filters.</p>
                                <a href="{{ route('leads.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Lead
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($leads->hasPages() || $leads->total() > 0)
            <div class="border-t border-gray-200 px-4 py-3">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-500">
                        Showing <span class="font-medium text-gray-700">{{ $leads->firstItem() ?: 0 }}</span> to <span class="font-medium text-gray-700">{{ $leads->lastItem() ?: 0 }}</span> of <span class="font-medium text-gray-700">{{ $leads->total() }}</span> leads
                    </p>
                    {{ $leads->links() }}
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
