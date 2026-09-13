<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountCategory;
use App\Models\AccountTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index()
    {
        $accountHolders = AccountCategory::with(['accounts' => fn ($q) => $q->orderBy('code')])
            ->orderByRaw("CASE name WHEN 'Cash' THEN 1 WHEN 'Bank' THEN 2 ELSE 3 END, name")
            ->get()
            ->filter(fn ($c) => $c->accounts->isNotEmpty());

        $categories = AccountCategory::orderBy('name')->get();

        return view('accounts.index', [
            'accountHolders' => $accountHolders,
            'categories' => $categories,
        ]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'category_name' => ['required', 'string', 'max:100', Rule::unique('account_categories', 'name')],
        ]);

        AccountCategory::create(['name' => $data['category_name']]);

        return back()->with('success', 'Account category "'.$data['category_name'].'" created successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:account_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', Rule::in(Account::TYPES)],
            'opening_balance' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(Account::STATUSES)],
        ]);

        $data['code'] = Account::generateCode();
        $data['current_balance'] = $data['opening_balance'] ?? 0;
        $data['opening_balance'] = $data['opening_balance'] ?? 0;

        Account::create($data);

        return back()->with('success', 'Account "'.$data['name'].'" created successfully.');
    }

    public function update(Request $request, Account $account)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'exists:account_categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', Rule::in(Account::TYPES)],
            'opening_balance' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(Account::STATUSES)],
        ]);

        $data['opening_balance'] = $data['opening_balance'] ?? 0;

        $account->update($data);

        return back()->with('success', 'Account "'.$account->name.'" updated successfully.');
    }

    public function destroy(Request $request, Account $account)
    {
        if ($account->transactions()->exists()) {
            return back()->with('error', 'This account has financial transactions. Deactivate the account instead of deleting it.');
        }

        $account->delete();

        return back()->with('success', 'Account "'.$account->name.'" deleted successfully.');
    }

    public function deactivate(Account $account)
    {
        $account->update(['status' => 'Inactive']);

        return back()->with('success', 'Account "'.$account->name.'" deactivated successfully.');
    }

    public function ledger(Request $request, Account $account)
    {
        $query = $account->transactions()->orderBy('date')->orderBy('id');

        $from = $request->get('from');
        $to = $request->get('to');
        $search = $request->get('search');

        if ($from) {
            $query->where('date', '>=', $from);
        }
        if ($to) {
            $query->where('date', '<=', $to);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $transactions = $query->get();

        $totalDebit = $transactions->sum('debit');
        $totalCredit = $transactions->sum('credit');
        $currentBalance = $account->opening_balance + $totalCredit - $totalDebit;

        $runningBalance = (float) $account->opening_balance;
        $transactions->each(function (AccountTransaction $transaction) use (&$runningBalance) {
            $runningBalance += (float) $transaction->credit - (float) $transaction->debit;
            $transaction->setAttribute('running_balance', $runningBalance);
        });

        return view('accounts.ledger', [
            'account' => $account->load('category'),
            'transactions' => $transactions,
            'openingBalance' => $account->opening_balance,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'currentBalance' => $currentBalance,
            'filters' => $request->only(['from', 'to', 'search']),
        ]);
    }
}