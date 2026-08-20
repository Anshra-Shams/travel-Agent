@php
    $isEdit = $isEdit ?? false;
    $q = $q ?? null;
    $requirement = $requirement ?? ($q?->serviceRequirement);
    $existingItems = $existingItems ?? ($q?->items->map(fn($i) => ['description' => $i->description, 'quantity' => $i->quantity, 'unit_price' => (float)$i->unit_price])->toArray() ?? [['description' => '', 'quantity' => 1, 'unit_price' => 0]]);
    $oldItems = old('items');
    if ($oldItems && is_array($oldItems)) {
        $existingItems = $oldItems;
    }
@endphp

<form method="POST" action="{{ $action }}"
      x-data="{
          items: @js($existingItems),
          discount: @js((float)($q->discount ?? old('discount', 0))),
          tax: @js((float)($q->tax ?? old('tax', 0))),
          deposit: @js((float)($q->deposit_required ?? old('deposit_required', 0))),
          get subtotal() { return this.items.reduce((s, i) => s + (i.quantity * i.unit_price), 0); },
          get grandTotal() { return this.subtotal - this.discount + this.tax; },
          get remaining() { return Math.max(0, this.grandTotal - this.deposit); },
          addItem() { this.items.push({ description: '', quantity: 1, unit_price: 0 }); },
          removeItem(i) { if (this.items.length > 1) this.items.splice(i, 1); },
          fmt(v) { return 'Rs. ' + Number(v).toLocaleString('en-IN', { maximumFractionDigits: 0 }); }
      }">
    @csrf
    @if ($isEdit) @method('PUT') @endif
    @if (!$isEdit) <input type="hidden" name="customer_service_id" value="{{ $requirement->id }}"> @endif

    <!-- ═══════ QUOTATION DOCUMENT ═══════ -->
    <div class="print-area mx-auto max-w-[900px] rounded-xl border border-gray-200 bg-white shadow-sm">

        <!-- ── Header ── -->
        <div class="border-b-2 border-sky-600 px-8 pt-8 pb-6">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                {{-- Agency Info --}}
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-sky-700">PROWAVE</h1>
                    <p class="mt-1 text-xs text-gray-400">Your Trusted Travel Partner</p>
                    <div class="mt-3 space-y-0.5 text-xs text-gray-500">
                        <p>123 Travel Street, Karachi, Pakistan</p>
                        <p>Phone: +92 300 1234567 | WhatsApp: +92 300 1234567</p>
                        <p>Email: info@travelagency.com | Web: www.travelagency.com</p>
                    </div>
                </div>
                {{-- Quotation Label & Meta --}}
                <div class="text-left sm:text-right">
                    <h2 class="text-3xl font-extrabold uppercase tracking-widest text-gray-900">Quotation</h2>
                    <div class="mt-3 space-y-1 text-sm">
                        <p class="font-bold text-sky-700">{{ $q->quotation_number ?? \App\Models\Quotation::generateNumber() }}</p>
                        <div>
                            <span class="text-gray-400">Date: </span>
                            <input type="date" name="quotation_date"
                                   value="{{ old('quotation_date', ($q->quotation_date ?? now())->format('Y-m-d')) }}"
                                   required
                                   class="inline-block w-[155px] rounded border border-gray-300 text-right text-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent print:text-sm">
                        </div>
                        <div>
                            <span class="text-gray-400">Valid Until: </span>
                            <input type="date" name="valid_until"
                                   value="{{ old('valid_until', ($q->valid_until ?? now()->addDays(14))->format('Y-m-d')) }}"
                                   required
                                   class="inline-block w-[155px] rounded border border-gray-300 text-right text-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent print:text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Customer & Service Info ── -->
        <div class="grid grid-cols-1 gap-0 border-b border-gray-200 sm:grid-cols-2">
            {{-- Customer --}}
            <div class="border-b border-gray-200 px-8 py-6 sm:border-b-0 sm:border-r">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Customer Information</h3>
                <div class="mt-3 space-y-1.5 text-sm">
                    <p class="font-semibold text-gray-900">{{ $requirement->customer->name }}</p>
                    <p class="text-gray-600">Phone: {{ $requirement->customer->phone }}</p>
                    @if ($requirement->customer->whatsapp)
                        <p class="text-gray-600">WhatsApp: {{ $requirement->customer->whatsapp }}</p>
                    @endif
                    @if ($requirement->customer->email)
                        <p class="text-gray-600">Email: {{ $requirement->customer->email }}</p>
                    @endif
                </div>
            </div>
            {{-- Service --}}
            <div class="px-8 py-6">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Service Information</h3>
                <div class="mt-3 space-y-1.5 text-sm">
                    <p class="text-gray-600"><span class="font-medium text-gray-800">Service:</span> {{ $requirement->service_type }}</p>
                    <input type="hidden" name="service_type" value="{{ $requirement->service_type }}">
                    <p class="text-gray-600"><span class="font-medium text-gray-800">Destination:</span> {{ $requirement->destination ?: '—' }}</p>
                    <input type="hidden" name="destination" value="{{ $requirement->destination }}">
                    <p class="text-gray-600"><span class="font-medium text-gray-800">Travel Date:</span> {{ $requirement->travel_date?->format('d M Y') ?: '—' }}</p>
                    <p class="text-gray-600"><span class="font-medium text-gray-800">Travelers:</span> {{ $requirement->travelers ?: '—' }}</p>
                </div>
            </div>
        </div>

        <!-- ── Requirements ── -->
        @if ($requirement->requirements)
            <div class="border-b border-gray-200 px-8 py-5">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Requirements</h3>
                <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $requirement->requirements }}</p>
            </div>
        @endif

        <!-- ── Items Table ── -->
        <div class="px-8 pt-6 pb-2">
            <div class="flex items-center justify-between print-hidden">
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Quotation Items</h3>
                <button type="button" @click="addItem()"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-sky-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-sky-700">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Item
                </button>
            </div>

            {{-- Desktop Table --}}
            <div class="mt-4 hidden sm:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-800 text-left text-[11px] font-bold uppercase tracking-wide text-gray-800">
                            <th class="pb-2 pr-4">Description</th>
                            <th class="pb-2 px-2 w-20 text-center">Qty</th>
                            <th class="pb-2 px-2 w-32 text-right">Unit Price</th>
                            <th class="pb-2 pl-2 w-32 text-right">Total</th>
                            <th class="pb-2 w-10 print-hidden"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="border-b border-gray-100">
                                <td class="py-2 pr-4">
                                    <input type="text" :name="'items[' + index + '][description]'" x-model="item.description" required
                                           placeholder="Description"
                                           class="block w-full rounded border border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent">
                                </td>
                                <td class="py-2 px-2">
                                    <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" min="1" required
                                           class="block w-full rounded border border-gray-300 text-center text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent print:text-center">
                                </td>
                                <td class="py-2 px-2">
                                    <input type="number" :name="'items[' + index + '][unit_price]'" x-model.number="item.unit_price" min="0" step="0.01" required
                                           class="block w-full rounded border border-gray-300 text-right text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent print:text-right">
                                </td>
                                <td class="py-2 pl-2 text-right text-sm font-semibold text-gray-900" x-text="fmt(item.quantity * item.unit_price)"></td>
                                <td class="py-2 text-center print-hidden">
                                    <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                            class="rounded p-1 text-gray-400 transition hover:bg-rose-50 hover:text-rose-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="mt-4 space-y-3 sm:hidden print-hidden">
                <template x-for="(item, index) in items" :key="index">
                    <div class="rounded-lg border border-gray-200 p-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-gray-400" x-text="'Item ' + (index + 1)"></span>
                            <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-gray-400 hover:text-rose-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <input type="text" :name="'items[' + index + '][description]'" x-model="item.description" required placeholder="Description"
                               class="mt-2 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-xs text-gray-400">Qty</label>
                                <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" min="1" required
                                       class="mt-0.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                            </div>
                            <div>
                                <label class="text-xs text-gray-400">Unit Price</label>
                                <input type="number" :name="'items[' + index + '][unit_price]'" x-model.number="item.unit_price" min="0" step="0.01" required
                                       class="mt-0.5 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                            </div>
                        </div>
                        <p class="mt-2 text-right text-sm font-medium text-gray-900" x-text="fmt(item.quantity * item.unit_price)"></p>
                    </div>
                </template>
            </div>
        </div>

        <!-- ── Totals ── -->
        <div class="flex justify-end px-8 py-4">
            <div class="w-full max-w-xs">
                <table class="w-full text-sm">
                    <tr>
                        <td class="py-1.5 text-gray-500">Subtotal</td>
                        <td class="py-1.5 text-right font-medium text-gray-900" x-text="fmt(subtotal)"></td>
                    </tr>
                    <tr>
                        <td class="py-1.5 text-gray-500">Discount</td>
                        <td class="py-1.5 text-right">
                            <input type="number" name="discount" x-model.number="discount" min="0" step="0.01"
                                   class="w-28 rounded border border-gray-300 text-right text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent print:text-right">
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1.5 text-gray-500">Tax</td>
                        <td class="py-1.5 text-right">
                            <input type="number" name="tax" x-model.number="tax" min="0" step="0.01"
                                   class="w-28 rounded border border-gray-300 text-right text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent print:text-right">
                        </td>
                    </tr>
                    <tr class="border-t-2 border-gray-800">
                        <td class="pt-2 text-base font-extrabold text-gray-900">Grand Total</td>
                        <td class="pt-2 text-right text-lg font-extrabold text-sky-700" x-text="fmt(grandTotal)"></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- ── Payment Terms ── -->
        <div class="border-t border-gray-200 px-8 py-6">
            <h3 class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Payment Terms</h3>
            <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="text-xs font-semibold text-gray-500">Payment Terms</label>
                    <textarea name="payment_terms" rows="2"
                              class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent"
                              placeholder="Describe payment terms...">{{ old('payment_terms', $q?->payment_terms ?? '') }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500">Deposit Required</label>
                    <input type="number" name="deposit_required" x-model.number="deposit" min="0" step="0.01"
                           class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500">Remaining Amount</label>
                    <div class="mt-1 block w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm font-medium text-sky-700" x-text="fmt(remaining)"></div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500">Payment Due Date</label>
                    <input type="date" name="payment_due_date"
                           value="{{ old('payment_due_date', $q?->payment_due_date?->format('Y-m-d') ?? '') }}"
                           class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent">
                </div>
            </div>
        </div>

        <!-- ── Notes & Terms ── -->
        <div class="border-t border-gray-200 px-8 py-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Notes</label>
                    <textarea name="notes" rows="3"
                              class="mt-2 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent"
                              placeholder="Internal notes...">{{ old('notes', $q?->notes ?? '') }}</textarea>
                </div>
                <div>
                    <label class="text-[11px] font-bold uppercase tracking-widest text-sky-600">Terms & Conditions</label>
                    <textarea name="terms_conditions" rows="3"
                              class="mt-2 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500 print:border-0 print:p-0 print:bg-transparent"
                              placeholder="Cancellation policy, refund terms, etc.">{{ old('terms_conditions', $q?->terms_conditions ?? '') }}</textarea>
                </div>
            </div>
        </div>

    </div><!-- /print-area -->

    <!-- ── Action Buttons ── -->
    <div class="no-print mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
        <a href="{{ $back }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            {{ $submitLabel }}
        </button>
    </div>
</form>
