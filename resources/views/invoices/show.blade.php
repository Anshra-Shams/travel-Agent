<x-admin-layout title="Invoice {{ $invoice->invoice_number }}">

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Invoice
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    {{ $invoice->invoice_number }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2">

                <a
                    href="{{ route('invoices.index') }}"
                    class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50"
                >
                    Back
                </a>

                <a
                    href="{{ route('invoices.edit', $invoice) }}"
                    class="px-4 py-2 rounded-lg bg-amber-500 text-white hover:bg-amber-600"
                >
                    Edit
                </a>

                <a
                    href="{{ route('invoices.print', $invoice) }}"
                    target="_blank"
                    class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"
                >
                    Print Invoice
                </a>

            </div>

        </div>

        @if (session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Invoice --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

            {{-- Company Header --}}
            <div class="px-8 py-7 border-b border-slate-200">

                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">

                    <div>
                        <h2 class="text-2xl font-bold text-slate-900">
                            IKRASH AL-MADINA
                        </h2>

                        <p class="text-sm font-medium text-indigo-600">
                            TRAVELS & TOURS
                        </p>

                        <div class="text-sm text-slate-500 mt-3 space-y-1">
                            <p>0316-3026092 | 0311-2211108</p>
                            <p>ikrashalmadinatravelntours@gmail.com</p>
                            <p>Facebook: IkrashAlMadinaTravels</p>
                        </div>
                    </div>

                    <div class="text-left md:text-right">

                        <div class="text-sm text-slate-500">
                            Invoice Number
                        </div>

                        <div class="text-xl font-bold text-slate-900">
                            {{ $invoice->invoice_number }}
                        </div>

                        <div class="text-sm text-slate-500 mt-3">
                            Issue Date
                        </div>

                        <div class="font-medium text-slate-900">
                            {{ $invoice->issued_at?->format('d M Y') ?? '-' }}
                        </div>

                    </div>

                </div>

            </div>

            {{-- Customer --}}
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-200">

                <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wide mb-3">
                    Bill To
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <div>
                        <div class="text-xs text-slate-500">
                            Customer
                        </div>

                        <div class="font-semibold text-slate-900">
                            {{ $invoice->customer->name }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500">
                            Phone
                        </div>

                        <div class="font-semibold text-slate-900">
                            {{ $invoice->customer->phone ?: '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-slate-500">
                            Email
                        </div>

                        <div class="font-semibold text-slate-900">
                            {{ $invoice->customer->email ?: '-' }}
                        </div>
                    </div>

                </div>

            </div>

            {{-- Service --}}
            <div class="px-8 py-6">

                <h3 class="text-lg font-bold text-slate-900 mb-4">
                    Invoice Details
                </h3>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead>
                            <tr class="border-b border-slate-200">

                                <th class="text-left py-3 text-xs uppercase text-slate-500">
                                    Service
                                </th>

                                <th class="text-left py-3 text-xs uppercase text-slate-500">
                                    Package
                                </th>

                                <th class="text-center py-3 text-xs uppercase text-slate-500">
                                    Qty
                                </th>

                                <th class="text-right py-3 text-xs uppercase text-slate-500">
                                    Unit Price
                                </th>

                                <th class="text-right py-3 text-xs uppercase text-slate-500">
                                    Total
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            <tr class="border-b border-slate-100">

                                <td class="py-5 font-medium text-slate-900">
                                    {{ $invoice->serviceType->name }}
                                </td>

                                <td class="py-5 text-slate-700">
                                    {{ $invoice->package->name }}
                                </td>

                                <td class="py-5 text-center">
                                    {{ $invoice->quantity }}
                                </td>

                                <td class="py-5 text-right">
                                    {{ number_format($invoice->unit_price, 2) }}
                                    {{ $invoice->currency }}
                                </td>

                                <td class="py-5 text-right font-semibold">
                                    {{ number_format($invoice->subtotal, 2) }}
                                    {{ $invoice->currency }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

            {{-- Totals --}}
            <div class="px-8 pb-8">

                <div class="flex justify-end">

                    <div class="w-full md:w-96 space-y-3">

                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">
                                Subtotal
                            </span>

                            <span class="font-medium">
                                {{ number_format($invoice->subtotal, 2) }}
                                {{ $invoice->currency }}
                            </span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">
                                Discount
                            </span>

                            <span class="font-medium text-red-600">
                                - {{ number_format($invoice->discount, 2) }}
                                {{ $invoice->currency }}
                            </span>
                        </div>

                        <div class="border-t border-slate-200 pt-3 flex justify-between text-lg">
                            <span class="font-bold text-slate-900">
                                Grand Total
                            </span>

                            <span class="font-bold text-indigo-600">
                                {{ number_format($invoice->grand_total, 2) }}
                                {{ $invoice->currency }}
                            </span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">
                                Paid
                            </span>

                            <span class="font-semibold text-emerald-600">
                                {{ number_format($invoice->paid_amount, 2) }}
                                {{ $invoice->currency }}
                            </span>
                        </div>

                        <div class="flex justify-between text-base border-t border-slate-200 pt-3">
                            <span class="font-bold text-slate-900">
                                Remaining
                            </span>

                            <span class="font-bold text-red-600">
                                {{ number_format($invoice->remaining_amount, 2) }}
                                {{ $invoice->currency }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>

            {{-- Status --}}
            <div class="px-8 py-5 bg-slate-50 border-t border-slate-200">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>
                        <span class="text-sm text-slate-500">
                            Status:
                        </span>

                        <span class="ml-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>

                    @if ($invoice->notes)
                        <div class="text-sm text-slate-600 md:text-right">
                            {{ $invoice->notes }}
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

</x-admin-layout>