<x-admin-layout :title="'Lead: ' . $lead->full_name">
    <!-- Page header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lead Details</h1>
            <p class="mt-1 text-sm text-gray-500">Complete information and activity of the lead.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('leads.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Leads
            </a>
            <a href="{{ route('leads.edit', $lead) }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Lead
            </a>
        </div>
    </div>

    <!-- Lead summary card -->
    <div class="mb-6 rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-indigo-600 text-lg font-bold text-white">
                    {{ strtoupper(substr($lead->full_name, 0, 1)) }}
                </span>
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-lg font-bold text-gray-900">{{ $lead->full_name }}</h2>
                        <x-status-badge :status="$lead->status" />
                    </div>
                    <p class="mt-1 text-sm text-gray-500">{{ $lead->phone }}@if ($lead->email) · {{ $lead->email }} @endif</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @if ($lead->customer)
                    <a href="{{ route('customers.show', $lead->customer) }}" class="inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700 transition hover:bg-emerald-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Converted to Customer
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif
                <form method="POST" action="{{ route('leads.destroy', $lead) }}" onsubmit="return confirm('Are you sure you want to delete this lead?');">
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
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Left column -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Lead information -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">Lead Information</h3>
                <dl class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Full Name</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $lead->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">WhatsApp</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->whatsapp ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Email</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->email ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Service Required</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-md bg-sky-50 px-2.5 py-0.5 text-sm font-medium text-sky-700 ring-1 ring-inset ring-sky-200">{{ $lead->service }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Travel Destination</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->destination ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Travel Date</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->travel_date ? $lead->travel_date->format('d M Y') : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Number of Travelers</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->travelers ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Lead Source</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->source ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Assigned Agent</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->assignedAgent?->name ?: 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Current Status</dt>
                        <dd class="mt-1"><x-status-badge :status="$lead->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Follow-up Date</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->follow_up_date ? $lead->follow_up_date->format('d M Y') : '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Created Date</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->created_at->format('d M Y, h:i A') }}</dd>
                    </div>
                </dl>

                @if ($lead->notes)
                    <div class="mt-5 border-t border-gray-100 pt-5">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Notes</dt>
                        <dd class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-700">{{ $lead->notes }}</dd>
                    </div>
                @endif
            </div>

            <!-- Follow-up history -->
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Follow-up History</h3>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">{{ $lead->followUps->count() }}</span>
                </div>
                @forelse ($lead->followUps as $followUp)
                    <div class="border-b border-gray-50 px-6 py-4 last:border-b-0">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-900">
                                    <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $followUp->follow_up_date ? $followUp->follow_up_date->format('d M Y') : 'No date' }}
                                </span>
                                @if ($followUp->status)
                                    <x-status-badge :status="$followUp->status" />
                                @endif
                                @if ($followUp->completed_at)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">
                                        Completed
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-400">by {{ $followUp->agent?->name ?: '—' }} · {{ $followUp->created_at->format('d M Y, h:i A') }}</span>
                                @if (!$followUp->completed_at && $followUp->follow_up_date)
                                    <form method="POST" action="{{ route('follow-ups.complete', $followUp) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-md border border-gray-200 px-2 py-1 text-xs font-medium text-gray-600 transition hover:bg-emerald-50 hover:text-emerald-600">Mark Complete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @if ($followUp->note)
                            <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $followUp->note }}</p>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm font-medium text-gray-500">No follow-ups yet</p>
                        <p class="mt-1 text-xs text-gray-400">Schedule a follow-up from the panel on the right.</p>
                    </div>
                @endforelse
            </div>

            <!-- Activity / call history -->
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h3 class="text-base font-semibold text-gray-900">Activity / Call History</h3>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">{{ $lead->activities->count() }}</span>
                </div>
                @php
                    $activityStyles = [
                        'system' => 'bg-slate-100 text-slate-500',
                        'status' => 'bg-violet-100 text-violet-600',
                        'note' => 'bg-sky-100 text-sky-600',
                        'assignment' => 'bg-indigo-100 text-indigo-600',
                        'follow-up' => 'bg-amber-100 text-amber-600',
                        'conversion' => 'bg-emerald-100 text-emerald-600',
                        'call' => 'bg-rose-100 text-rose-600',
                    ];
                @endphp
                @forelse ($lead->activities as $activity)
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
            <!-- Status & assignment -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">Status & Assignment</h3>

                <form method="POST" action="{{ route('leads.status', $lead) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')
                    <x-input-label value="Change Status" />
                    <select name="status" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected($lead->status === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-violet-700">
                        Update Status
                    </button>
                </form>

                <form method="POST" action="{{ route('leads.assign', $lead) }}" class="mt-4 space-y-3 border-t border-gray-100 pt-4">
                    @csrf
                    @method('PATCH')
                    <x-input-label value="Assign Agent" />
                    <select name="agent_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="">Unassigned</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}" @selected($lead->agent_id === $agent->id)>{{ $agent->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        Assign Agent
                    </button>
                </form>

                @if ($lead->customer)
                    <div class="mt-4 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <a href="{{ route('customers.show', $lead->customer) }}" class="text-sm font-semibold text-emerald-800 transition hover:text-emerald-900">Converted to customer</a>
                            <p class="mt-0.5 text-xs text-emerald-600">This lead is already a customer. No duplicate record was created.</p>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('leads.convert', $lead) }}" class="mt-4 border-t border-gray-100 pt-4" onsubmit="return confirm('Convert this lead to a customer?');">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Convert to Customer
                        </button>
                    </form>
                @endif
            </div>

            <!-- Add follow-up -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">Add Follow-up</h3>
                <form method="POST" action="{{ route('leads.follow-ups.store', $lead) }}" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <x-input-label value="Follow-up Date" />
                        <x-text-input type="date" name="follow_up_date" class="mt-1.5 block w-full" :value="old('follow_up_date', now()->addDays(1)->format('Y-m-d'))" />
                        <x-input-error :messages="$errors->get('follow_up_date')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label value="Status" />
                        <select name="status" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                            <option value="">No change</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" @selected($lead->status === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
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
        </div>
    </div>
</x-admin-layout>
