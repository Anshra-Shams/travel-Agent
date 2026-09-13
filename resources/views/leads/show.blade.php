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
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Customer Name</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $lead->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->phone }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Lead Source</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->source ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Interested Service</dt>
                        <dd class="mt-1">
                            <div class="flex flex-wrap gap-1">
                                @forelse ($lead->services_list as $svc)
                                    <span class="inline-flex items-center rounded-md bg-sky-50 px-2.5 py-0.5 text-sm font-medium text-sky-700 ring-1 ring-inset ring-sky-200">{{ $svc }}</span>
                                @empty
                                    <span class="text-sm text-gray-400">—</span>
                                @endforelse
                            </div>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Assigned Agent</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->assignedAgent?->name ?: 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Status</dt>
                        <dd class="mt-1"><x-status-badge :status="$lead->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Next Follow-up Date</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->follow_up_date ? $lead->follow_up_date->format('d M Y') : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Next Follow-up Time</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $lead->follow_up_time ? \Carbon\Carbon::parse($lead->follow_up_time)->format('h:i A') : '—' }}</dd>
                    </div>
                </dl>

                <div class="mt-5 border-t border-gray-100 pt-5">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Requirements / Notes</dt>
                    <dd class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-700">{{ $lead->notes ?: '—' }}</dd>
                </div>
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

            </div>

        <!-- Right column -->
        <div class="space-y-6">
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
