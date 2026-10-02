<x-admin-layout title="Invoices">

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Invoices
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Manage customer invoices, payments and balances.
                </p>
            </div>

            <a href="{{ route('invoices.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 4v16m8-8H4"/>
                </svg>

                Create Invoice
            </a>
        </div>


        {{-- Success Message --}}
        @if(session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif


        {{-- Error Message --}}
        @if(session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif


        {{-- Filters --}}
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

            <form method="GET"
                  action="{{ route('invoices.index') }}"
                  class="grid grid-cols-1 gap-4 md:grid-cols-3">

                {{-- Search --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Invoice number, customer name or phone"
                        class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-500 focus:ring-gray-500"
                    >
                </div>


                {{-- Status --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-500 focus:ring-gray-500"
                    >
                        <option value="">All Statuses</option>

                        @foreach($statuses as $status)
                            <option value="{{ $status }}"
                                @selected(request('status') === $status)>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- Buttons --}}
                <div class="flex items-end gap-2">

                    <button
                        type="submit"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800"
                    >
                        Search
                    </button>

                    <a
                        href="{{ route('invoices.index') }}"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Clear
                    </a>

                </div>

            </form>

        </div>


        {{-- Invoice Table --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Invoice
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Customer
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Service
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Total
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Paid
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Remaining
                            </th>

                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Status
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 bg-white">

                        @forelse($invoices as $invoice)

                            <tr class="hover:bg-gray-50">

                                {{-- Invoice Number --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <a
                                        href="{{ route('invoices.show', $invoice) }}"
                                        class="font-semibold text-gray-900 hover:text-blue-600"
                                    >
                                        {{ $invoice->invoice_number }}
                                    </a>

                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ optional($invoice->issued_at)->format('d M Y') ?? '-' }}
                                    </div>

                                </td>


                                {{-- Customer --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-900">
                                        {{ $invoice->customer?->name ?? 'N/A' }}
                                    </div>

                                    @if($invoice->customer?->phone)
                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $invoice->customer->phone }}
                                        </div>
                                    @endif

                                </td>


                                {{-- Service --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-900">
                                        {{ $invoice->serviceType?->name ?? 'N/A' }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $invoice->package?->name ?? 'N/A' }}
                                    </div>

                                </td>


                                {{-- Total --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <div class="font-semibold text-gray-900">
                                        {{ $invoice->currency }}
                                        {{ number_format($invoice->grand_total, 2) }}
                                    </div>

                                </td>


                                {{-- Paid --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <div class="font-medium text-green-600">
                                        {{ $invoice->currency }}
                                        {{ number_format($invoice->paid_amount, 2) }}
                                    </div>

                                </td>


                                {{-- Remaining --}}
                                <td class="whitespace-nowrap px-6 py-4 text-right">

                                    <div class="font-medium {{ $invoice->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $invoice->currency }}
                                        {{ number_format($invoice->remaining_amount, 2) }}
                                    </div>

                                </td>


                                {{-- Status --}}
                                <td class="whitespace-nowrap px-6 py-4 text-center">

                                    @php
                                        $statusClasses = [
                                            'draft' => 'bg-gray-100 text-gray-700',
                                            'sent' => 'bg-blue-100 text-blue-700',
                                            'partial' => 'bg-yellow-100 text-yellow-700',
                                            'paid' => 'bg-green-100 text-green-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp

                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>

                                </td>


                                {{-- Actions --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <div class="flex items-center justify-end gap-2">

                                        {{-- View --}}
                                        <a
                                            href="{{ route('invoices.show', $invoice) }}"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                        >
                                            View
                                        </a>


                                        {{-- Edit --}}
                                        <a
                                            href="{{ route('invoices.edit', $invoice) }}"
                                            class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100"
                                        >
                                            Edit
                                        </a>


                                        {{-- Print --}}
                                        <a
                                            href="{{ route('invoices.print', $invoice) }}"
                                            target="_blank"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                                        >
                                            Print
                                        </a>


                                        {{-- Delete --}}
                                        <form
                                            method="POST"
                                            action="{{ route('invoices.destroy', $invoice) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this invoice?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-100"
                                            >
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="px-6 py-12 text-center"
                                >

                                    <div class="mx-auto max-w-md">

                                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-7 w-7 text-gray-400"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.5">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M9 14.25l2.25 2.25L15 12.75M7.5 3.75h9A2.25 2.25 0 0118.75 6v14.25l-3-1.5-3 1.5-3-1.5-3 1.5-3-1.5V6A2.25 2.25 0 017.5 3.75z" />
                                            </svg>

                                        </div>

                                        <h3 class="text-base font-semibold text-gray-900">
                                            No invoices found
                                        </h3>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Create your first invoice to start managing customer payments.
                                        </p>

                                        <div class="mt-5">

                                            <a
                                                href="{{ route('invoices.create') }}"
                                                class="inline-flex items-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800"
                                            >
                                                Create Invoice
                                            </a>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($invoices->hasPages())

                <div class="border-t border-gray-200 px-6 py-4">

                    {{ $invoices->links() }}

                </div>

            @endif

        </div>

    </div>

</x-admin-layout>