<x-admin-layout title="Quotation {{ $quotation->quotation_number }}">
    {{-- ═══════ Action Bar (hidden on print) ═══════ --}}
    <div class="no-print mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quotation {{ $quotation->quotation_number }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $quotation->service_type }} for {{ $quotation->customer->name }}
                <span class="ml-2 inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $quotation->status_color }}">{{ $quotation->status }}</span>
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('quotations.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back
            </a>
            @if ($quotation->status === 'Draft')
                <a href="{{ route('quotations.edit', $quotation) }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('quotations.status', $quotation) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="Sent">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send
                    </button>
                </form>
            @endif
            @if (in_array($quotation->status, ['Draft', 'Sent']))
                <form method="POST" action="{{ route('quotations.destroy', $quotation) }}" onsubmit="return confirm('Delete this quotation?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-100">Delete</button>
                </form>
            @endif
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print Quotation
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="no-print mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    {{-- ═══════ Status Banners (hidden on print) ═══════ --}}
    @if ($quotation->status === 'Sent')
        <div class="no-print mb-6 rounded-xl border border-sky-200 bg-sky-50 p-4 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm font-medium text-sky-800">This quotation has been sent. Update based on customer response:</p>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('quotations.status', $quotation) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Accepted">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700">Accept</button>
                    </form>
                    <form method="POST" action="{{ route('quotations.status', $quotation) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-white px-4 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">Reject</button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if ($quotation->status === 'Accepted')
        <div class="no-print mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm font-medium text-emerald-800">Quotation accepted. You can now create a booking.</p>
                <span class="inline-flex items-center gap-2 rounded-lg border border-emerald-300 bg-white px-4 py-2 text-sm font-semibold text-emerald-700">Create Booking (coming soon)</span>
            </div>
        </div>
    @endif

    @if ($quotation->status === 'Rejected')
        <div class="no-print mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm font-medium text-amber-800">Quotation rejected. Schedule a follow-up with the customer.</p>
                <a href="{{ route('customers.show', $quotation->customer) }}" class="inline-flex items-center gap-2 rounded-lg border border-amber-300 bg-white px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-50">Schedule Follow-up</a>
            </div>
        </div>
    @endif

    {{-- ═══════ QUOTATION DOCUMENT ═══════ --}}
    <div class="print-area mx-auto max-w-[900px] rounded-xl border border-gray-200 bg-white shadow-sm">

        <!-- Header -->
        <div class="border-b-2 border-sky-600 px-8 pt-8 pb-6">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-sky-700">PROWAVE</h1>
                    <p class="mt-1 text-xs text-gray-400">Your Trusted Travel Partner</p>
                    <div class="mt-3 space-y-0.5 text-xs text-gray-500">
                        <p>123 Travel Street, Karachi, Pakistan</p>
                        <p>Phone: +92 300 1234567 | WhatsApp: +92 300 1234567</p>
                        <p>Email: info@travelagency.com | Web: www.travelagency.com</p>
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <h2 class="text-3xl font-extrabold uppercase tracking-widest text-gray-900">Quotation</h2>
                    <div class="mt-3 space-y-1 text-sm">
                        <p class="font-bold text-sky-700">{{ $quotation->quotation_number }}</p>
                        <p class="text-gray-600">Date: {{ $quotation->quotation_date->format('d M Y') }}</p>
                        <p class="text-gray-600">Valid Until: {{ $quotation->valid_until->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer & Service -->
        <div class="grid grid-cols-1 gap-0 border-b border-gray-200 sm:grid-cols-2">
            <div class="border-b border-gray-200 px-8 py-6 sm:border-b-0 sm:border-r">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Customer Information</h3>
                <div class="mt-3 space-y-1.5 text-sm">
                    <p class="font-semibold text-gray-900">{{ $quotation->customer->name }}</p>
                    <p class="text-gray-600">Phone: {{ $quotation->customer->phone }}</p>
                    @if ($quotation->customer->whatsapp)
                        <p class="text-gray-600">WhatsApp: {{ $quotation->customer->whatsapp }}</p>
                    @endif
                    @if ($quotation->customer->email)
                        <p class="text-gray-600">Email: {{ $quotation->customer->email }}</p>
                    @endif
                </div>
            </div>
            <div class="px-8 py-6">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Service Information</h3>
                <div class="mt-3 space-y-1.5 text-sm">
                    <p class="text-gray-600"><span class="font-medium text-gray-800">Service:</span> {{ $quotation->service_type }}</p>
                    @if ($quotation->destination)
                        <p class="text-gray-600"><span class="font-medium text-gray-800">Destination:</span> {{ $quotation->destination }}</p>
                    @endif
                    @if ($quotation->serviceRequirement?->travel_date)
                        <p class="text-gray-600"><span class="font-medium text-gray-800">Travel Date:</span> {{ $quotation->serviceRequirement->travel_date->format('d M Y') }}</p>
                    @endif
                    @if ($quotation->serviceRequirement?->travelers)
                        <p class="text-gray-600"><span class="font-medium text-gray-800">Travelers:</span> {{ $quotation->serviceRequirement->travelers }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="px-8 pt-6 pb-2">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-800 text-left text-[11px] font-bold uppercase tracking-wide text-gray-800">
                        <th class="pb-2 pr-4">Description</th>
                        <th class="pb-2 px-2 w-20 text-center">Qty</th>
                        <th class="pb-2 px-2 w-32 text-right">Unit Price</th>
                        <th class="pb-2 pl-2 w-32 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($quotation->items as $item)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 pr-4 text-gray-900">{{ $item->description }}</td>
                            <td class="py-3 px-2 text-center text-gray-700">{{ $item->quantity }}</td>
                            <td class="py-3 px-2 text-right text-gray-700">Rs. {{ number_format($item->unit_price, 0) }}</td>
                            <td class="py-3 pl-2 text-right font-semibold text-gray-900">Rs. {{ number_format($item->total, 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-8 text-center text-sm text-gray-400">No items</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="flex justify-end px-8 py-4">
            <div class="w-full max-w-xs">
                <table class="w-full text-sm">
                    <tr>
                        <td class="py-1.5 text-gray-500">Subtotal</td>
                        <td class="py-1.5 text-right font-medium text-gray-900">Rs. {{ number_format($quotation->subtotal, 0) }}</td>
                    </tr>
                    @if ($quotation->discount > 0)
                        <tr>
                            <td class="py-1.5 text-gray-500">Discount</td>
                            <td class="py-1.5 text-right font-medium text-rose-600">- Rs. {{ number_format($quotation->discount, 0) }}</td>
                        </tr>
                    @endif
                    @if ($quotation->tax > 0)
                        <tr>
                            <td class="py-1.5 text-gray-500">Tax</td>
                            <td class="py-1.5 text-right font-medium text-gray-900">+ Rs. {{ number_format($quotation->tax, 0) }}</td>
                        </tr>
                    @endif
                    <tr class="border-t-2 border-gray-800">
                        <td class="pt-2 text-base font-extrabold text-gray-900">Grand Total</td>
                        <td class="pt-2 text-right text-lg font-extrabold text-sky-700">Rs. {{ number_format($quotation->grand_total, 0) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Payment Terms -->
        @if ($quotation->payment_terms || $quotation->deposit_required || $quotation->payment_due_date)
            <div class="border-t border-gray-200 px-8 py-6">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Payment Terms</h3>
                @if ($quotation->payment_terms)
                    <p class="mt-2 whitespace-pre-line text-sm text-gray-600">{{ $quotation->payment_terms }}</p>
                @endif
                <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    @if ($quotation->deposit_required)
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2">
                            <p class="text-[11px] font-semibold uppercase text-gray-400">Deposit Required</p>
                            <p class="text-sm font-bold text-gray-900">Rs. {{ number_format($quotation->deposit_required, 0) }}</p>
                        </div>
                    @endif
                    @if ($quotation->remaining_amount !== null)
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2">
                            <p class="text-[11px] font-semibold uppercase text-gray-400">Remaining</p>
                            <p class="text-sm font-bold text-sky-700">Rs. {{ number_format($quotation->remaining_amount, 0) }}</p>
                        </div>
                    @endif
                    @if ($quotation->payment_due_date)
                        <div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2">
                            <p class="text-[11px] font-semibold uppercase text-gray-400">Payment Due</p>
                            <p class="text-sm font-bold text-gray-900">{{ $quotation->payment_due_date->format('d M Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Notes & Terms -->
        @if ($quotation->notes || $quotation->terms_conditions)
            <div class="border-t border-gray-200 px-8 py-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    @if ($quotation->notes)
                        <div>
                            <h3 class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Notes</h3>
                            <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $quotation->notes }}</p>
                        </div>
                    @endif
                    @if ($quotation->terms_conditions)
                        <div>
                            <h3 class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Terms & Conditions</h3>
                            <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $quotation->terms_conditions }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div><!-- /print-area -->

    {{-- ═══════ Sidebar cards (hidden on print) ═══════ --}}
    <div class="no-print mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2"></div>
        <div class="space-y-6">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-gray-900">Activity History</h3>
                @forelse ($quotation->activities as $activity)
                    <div class="mt-3 border-b border-gray-50 py-2 last:border-b-0">
                        <p class="text-sm text-gray-800">{{ $activity->description }}</p>
                        <p class="mt-0.5 text-xs text-gray-400">{{ $activity->agent?->name ?: 'System' }} · {{ $activity->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                @empty
                    <p class="mt-3 text-sm text-gray-500">No activity yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
