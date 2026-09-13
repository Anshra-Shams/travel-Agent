<x-admin-layout title="{{ $account->name }} - Ledger">
    <div class="space-y-6">
        <!-- Back link -->
        <a href="{{ route('accounts.index') }}" class="inline-flex items-center gap-2 rounded-lg text-sm font-semibold text-gray-600 transition hover:text-sky-600">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Chart of Accounts
        </a>

        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Account Ledger</h1>
                <p class="mt-1 text-sm text-gray-500">Complete financial history for {{ $account->name }}</p>
            </div>
            <span class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-200">{{ $account->code }}</span>
        </div>

        <!-- Summary stats -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Opening Balance</p>
                <p class="mt-1 text-xl font-bold text-gray-900">{{ number_format((float) $openingBalance, 0) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Total Debit</p>
                <p class="mt-1 text-xl font-bold text-rose-600">{{ number_format((float) $totalDebit, 0) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Total Credit</p>
                <p class="mt-1 text-xl font-bold text-emerald-600">{{ number_format((float) $totalCredit, 0) }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Current Balance</p>
                <p class="mt-1 text-xl font-bold text-sky-700">{{ number_format((float) $currentBalance, 0) }}</p>
            </div>
        </div>

        <!-- Date range filter -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('accounts.ledger', $account) }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                <div>
                    <x-input-label value="From Date" />
                    <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="mt-1 block rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                </div>
                <div>
                    <x-input-label value="To Date" />
                    <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="mt-1 block rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                </div>
                <div class="flex-1">
                    <x-input-label value="Search" />
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by reference or description" class="mt-1 block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-sky-500 focus:ring-sky-500">
                </div>
                <div class="flex items-center gap-3 pt-5">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Search
                    </button>
                    @if ($filters['from'] ?? $filters['to'] ?? $filters['search'] ?? null)
                        <a href="{{ route('accounts.ledger', $account) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Ledger table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Reference</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Description</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Debit</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Credit</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($transactions as $transaction)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">{{ $transaction->date->format('d M Y') }}</td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="rounded-md bg-slate-100 px-2 py-0.5 font-mono text-xs font-semibold text-slate-700">{{ $transaction->reference ?: '—' }}</span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $transaction->description ?: '—' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    @if ((float) $transaction->debit > 0)
                                        <span class="text-sm font-medium text-rose-600">{{ number_format((float) $transaction->debit, 0) }}</span>
                                    @else
                                        <span class="text-sm text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    @if ((float) $transaction->credit > 0)
                                        <span class="text-sm font-medium text-emerald-600">{{ number_format((float) $transaction->credit, 0) }}</span>
                                    @else
                                        <span class="text-sm text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right font-semibold text-gray-900">{{ number_format((float) $transaction->running_balance, 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center">
                                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                                        <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </span>
                                    <p class="mt-4 text-sm font-medium text-gray-500">No transactions found</p>
                                    <p class="mt-1 text-xs text-gray-400">Payments recorded against this account will appear here.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>