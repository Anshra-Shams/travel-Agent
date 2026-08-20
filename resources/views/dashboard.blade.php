<x-admin-layout title="Dashboard">
    <!-- Page header -->
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="mt-1 text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <a href="{{ route('leads.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New Lead
        </a>
    </div>

    <!-- Stat cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        @php
            $stats = [
                ['label' => 'Total Leads', 'value' => $totalLeads, 'note' => 'All leads', 'icon' => 'user-plus', 'chip' => 'bg-sky-50 text-sky-600'],
                ['label' => 'Total Customers', 'value' => $totalCustomers, 'note' => 'All customers', 'icon' => 'users', 'chip' => 'bg-emerald-50 text-emerald-600'],
                ['label' => 'Active Bookings', 'value' => $activeBookings, 'note' => 'In progress', 'icon' => 'calendar', 'chip' => 'bg-indigo-50 text-indigo-600'],
                ['label' => 'Pending Payments', 'value' => $pendingPayments, 'note' => 'Awaiting payment', 'icon' => 'banknotes', 'chip' => 'bg-amber-50 text-amber-600'],
                ['label' => 'Upcoming Travel', 'value' => $upcomingTravel, 'note' => 'Next 30 days', 'icon' => 'clock', 'chip' => 'bg-rose-50 text-rose-600'],
            ];

            $icons = [
                'user-plus' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
                'users'     => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                'calendar'  => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                'banknotes' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'clock'     => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stat['value']) }}</p>
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-lg {{ $stat['chip'] }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{!! $icons[$stat['icon']] !!}" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-xs text-gray-400">{{ $stat['note'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Dashboard sections -->
    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Recent Leads -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                <h3 class="font-semibold text-gray-900">Recent Leads</h3>
                <a href="{{ route('leads.index') }}" class="text-sm font-medium text-sky-600 transition hover:text-sky-700">View all</a>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Lead</th>
                        <th class="px-5 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Service</th>
                        <th class="px-5 py-2.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($recentLeads as $lead)
                        <tr class="transition hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <a href="{{ route('leads.show', $lead) }}" class="text-sm font-medium text-gray-900 hover:text-sky-600">{{ $lead->full_name }}</a>
                                <p class="text-xs text-gray-400">{{ $lead->phone }}</p>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-600">{{ $lead->service }}</td>
                            <td class="px-5 py-3 text-right"><x-status-badge :status="$lead->status" /></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center">
                                <p class="text-sm font-medium text-gray-500">No leads yet</p>
                                <p class="mt-1 text-xs text-gray-400">New leads will appear here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Recent Bookings -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                <h3 class="font-semibold text-gray-900">Recent Bookings</h3>
                <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-sky-600 transition hover:text-sky-700">View all</a>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Customer</th>
                        <th class="px-5 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Service</th>
                        <th class="px-5 py-2.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <tr>
                        <td colspan="3" class="px-5 py-10 text-center">
                            <p class="text-sm font-medium text-gray-500">No bookings yet</p>
                            <p class="mt-1 text-xs text-gray-400">New bookings will appear here.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Upcoming Follow-ups -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                <h3 class="font-semibold text-gray-900">Upcoming Follow-ups</h3>
                <a href="{{ route('follow-ups.index') }}" class="text-sm font-medium text-sky-600 transition hover:text-sky-700">View all</a>
            </div>
            @forelse ($upcomingFollowUps as $lead)
                <a href="{{ route('leads.show', $lead) }}" class="flex items-center justify-between gap-3 border-b border-gray-50 px-5 py-3.5 transition last:border-b-0 hover:bg-gray-50">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-gray-900">{{ $lead->full_name }}</p>
                        <p class="truncate text-xs text-gray-400">{{ $lead->service }}{{ $lead->assignedAgent?->name ? ' · ' . $lead->assignedAgent->name : '' }}</p>
                    </div>
                    <span class="inline-flex flex-shrink-0 items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-200">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $lead->follow_up_date->format('d M') }}
                    </span>
                </a>
            @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-sm font-medium text-gray-500">No follow-ups scheduled</p>
                    <p class="mt-1 text-xs text-gray-400">Upcoming follow-ups will appear here.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
