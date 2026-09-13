<x-admin-layout title="{{ $quotation->quotation_number }} - Invoice">
    <style>
        @media print {
            body * { visibility: hidden; }
            .invoice-area, .invoice-area * { visibility: visible !important; }
            .invoice-area { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    </style>

    <div class="mx-auto max-w-3xl space-y-5 no-print">
        <div class="flex items-center justify-between">
            <a href="{{ route('quotations.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to Bookings
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Print Invoice
            </button>
        </div>
    </div>

    <!-- Invoice -->
    <div class="invoice-area mx-auto max-w-3xl">
        <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm">

            <!-- Header -->
            <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between border-b border-gray-200 pb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Travel Agent</h1>
                    <p class="mt-1 text-sm text-gray-500">Your Trusted Travel Partner</p>
                </div>
                <div class="text-left sm:text-right">
                    <div class="flex items-center gap-2 sm:justify-end">
                        <h2 class="text-xl font-bold text-gray-900">{{ $quotation->quotation_number }}</h2>
                        @if ($quotation->is_booking)
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-200">BOOKING</span>
                        @else
                            <span class="inline-flex items-center whitespace-nowrap rounded-full bg-violet-50 px-2.5 py-0.5 text-xs font-semibold text-violet-700 ring-1 ring-inset ring-violet-200">QUOTATION</span>
                        @endif
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Date: {{ $quotation->created_at->format('d M Y') }}</p>
                    @if ($quotation->valid_until && !$quotation->is_booking)
                        <p class="text-sm text-gray-500">Valid Until: {{ $quotation->valid_until->format('d M Y') }}</p>
                    @endif
                </div>
            </div>

            <!-- Bill To & Service Info -->
            <div class="grid grid-cols-1 gap-8 py-6 sm:grid-cols-2 border-b border-gray-200">
                <div class="text-left">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Bill To</p>
                    <p class="text-sm font-bold text-gray-900">{{ $quotation->customer->name }}</p>
                    @if ($quotation->customer->phone)
                        <p class="text-sm text-gray-600">{{ $quotation->customer->phone }}</p>
                    @endif
                    @if ($quotation->customer->email)
                        <p class="text-sm text-gray-600">{{ $quotation->customer->email }}</p>
                    @endif
                </div>
                <div class="text-left">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Service Details</p>
                    <p class="text-sm font-bold text-gray-900">{{ implode(', ', $quotation->services_list) }}</p>
                    @if ($quotation->destination)
                        <p class="text-sm text-gray-600">Destination: {{ $quotation->destination }}</p>
                    @endif
                    @if ($quotation->travel_date)
                        <p class="text-sm text-gray-600">Travel Date: {{ $quotation->travel_date->format('d M Y') }}</p>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <div class="overflow-hidden rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Service</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Destination</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Travelers</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($quotation->services_list as $index => $service)
                            @php
                                $svcAmount = \App\Models\ServiceType::where('name', $service)->value('amount') ?? 0;
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $service }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $quotation->destination ?: '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $quotation->serviceRequirement?->travelers ?: '—' }}</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rs. {{ number_format((float) $svcAmount, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="flex justify-end pt-6">
                <div class="w-full max-w-xs space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-medium text-gray-900">Rs. {{ number_format((float) $quotation->subtotal, 0) }}</span>
                    </div>
                    @if ((float) $quotation->discount > 0)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Discount</span>
                            <span class="font-medium text-rose-600">- Rs. {{ number_format((float) $quotation->discount, 0) }}</span>
                        </div>
                    @endif
                    <div class="border-t border-gray-200 pt-2 flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-900">Grand Total</span>
                        <span class="text-lg font-bold text-gray-900">Rs. {{ number_format((float) $quotation->grand_total, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Info (Booking only) -->
            @if ($quotation->is_booking)
                <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Payment Information</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Payment Status</span>
                            <span class="ml-2 inline-flex items-center whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $quotation->payment_status_color }}">{{ $quotation->payment_status ?: 'Pending' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Deposit Paid</span>
                            <span class="ml-2 font-semibold text-gray-900">Rs. {{ number_format((float) $quotation->deposit_required, 0) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Remaining</span>
                            <span class="ml-2 font-semibold text-amber-600">Rs. {{ number_format((float) $quotation->remaining_amount, 0) }}</span>
                        </div>
                        @if ($quotation->account)
                            <div>
                                <span class="text-gray-500">Account</span>
                                <span class="ml-2 font-semibold text-gray-900">{{ $quotation->account->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Notes -->
            @if ($quotation->notes)
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">Notes</p>
                    <p class="whitespace-pre-wrap text-sm text-gray-600">{{ $quotation->notes }}</p>
                </div>
            @endif

            <!-- Footer -->
            <div class="mt-8 border-t border-gray-200 pt-4 text-center text-xs text-gray-400">
                <p>Thank you for your business!</p>
            </div>
        </div>
    </div>
</x-admin-layout>
