<x-admin-layout title="Chart of Accounts">
    <div class="space-y-6" x-data="{
        showCategoryModal: @js($errors->has('category_name')),
        showAccountModal: @js($errors->hasAny(['category_id', 'name', 'type', 'opening_balance', 'status'])),
        categoryForm: { name: @js(old('category_name', '')) },
        accountForm: {
            code: '',
            category_id: @js(old('category_id', '')),
            name: @js(old('name', '')),
            type: @js(old('type', 'Debit')),
            opening_balance: @js(old('opening_balance', '0')),
            status: @js(old('status', 'Active')),
        },
        modalMode: 'add',
        editingAccount: null,
        openAddAccount() {
            this.modalMode = 'add';
            this.editingAccount = null;
            this.accountForm = { code: '', category_id: '', name: '', type: 'Debit', opening_balance: '0', status: 'Active' };
            this.showAccountModal = true;
        },
        openEditAccount(account) {
            this.modalMode = 'edit';
            this.editingAccount = account;
            this.accountForm = {
                code: account.code,
                category_id: account.category_id || '',
                name: account.name,
                type: account.type,
                opening_balance: account.opening_balance,
                status: account.status,
            };
            this.showAccountModal = true;
        },
        menuOpen: false,
        menuStyle: '',
        menuData: { ledger: '', edit: {}, destroy: '', has_transactions: false },
        openMenu(btn, data) {
            const r = btn.getBoundingClientRect();
            const w = 215;
            const estimate = 3 * 40 + 16;
            let top = r.bottom + 4;
            if (window.innerHeight - top < estimate) {
                top = Math.max(8, r.top - estimate - 4);
            }
            const right = Math.max(8, Math.min(window.innerWidth - r.right, w));
            this.menuStyle = 'position: fixed; top:' + top + 'px; right:' + right + 'px; width:' + w + 'px;';
            this.menuData = data;
            this.menuOpen = true;
        }
    }">

        <!-- Page header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Chart of Accounts</h1>
                <p class="mt-1 text-sm text-gray-500">Manage financial accounts and account categories</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" @click="showCategoryModal = true" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Account Category
                </button>
                <button type="button" @click="openAddAccount()" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Account
                </button>
            </div>
        </div>

        <!-- Accounts table -->
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Head / Group</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Account Title</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Type</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Opening Balance</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Current Balance</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($accountHolders as $group)
                                @php $rowNumber = 0; @endphp
                                <tr class="bg-slate-50">
                                    <td colspan="9" class="px-4 py-2.5">
                                        <div class="flex items-center justify-between">
                                            <span class="inline-flex items-center gap-2 text-sm font-semibold text-gray-800">
                                                <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    @if ($group->name === 'Cash')
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0l3-3m-3 3l-3-3m6 6v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2m10-8a2 2 0 112 2M6 8a2 2 0 112-2m10 0a2 2 0 11-2 2M6 12a2 2 0 11-2 2m10 0a2 2 0 11-2 2m-8-2a2 2 0 112 2" />
                                                    @elseif ($group->name === 'Bank')
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l9-4 9 4m-18 0v4m18-4v4M4 10h16v3H4v-3zm1 3v7m5-7v7m5-7v7M3 20h18" />
                                                    @else
                                                        <rect x="3" y="4" width="18" height="16" rx="2" />
                                                    @endif
                                                </svg>
                                                <span>{{ $group->name }} Accounts</span>
                                            </span>
                                            <span class="inline-flex items-center rounded-full bg-white px-2.5 py-0.5 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200">{{ $group->accounts->count() }} account(s)</span>
                                        </div>
                                    </td>
                                </tr>
                                @foreach ($group->accounts as $account)
                                    @php $rowNumber++; @endphp
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $rowNumber }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="rounded-md bg-slate-100 px-2 py-0.5 font-mono text-xs font-semibold text-slate-700">{{ $account->code }}</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-medium text-sky-700 ring-1 ring-inset ring-sky-200">{{ $account->head }}</span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="text-sm font-medium text-gray-900">{{ $account->name }}</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-700">{{ $account->type }}</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right">
                                            <span class="text-sm text-gray-600">{{ number_format((float) $account->opening_balance, 0) }}</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right">
                                            <span class="text-sm font-semibold text-gray-900">{{ number_format((float) $account->current_balance, 0) }}</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @if ($account->status === 'Active')
                                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-200">Active</span>
                                            @else
                                                <span class="inline-flex items-center whitespace-nowrap rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-medium text-rose-700 ring-1 ring-inset ring-rose-200">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-end">
                                                <button type="button" @click="openMenu($el, @js([
                                                    'ledger' => route('accounts.ledger', $account),
                                                    'edit' => [
                                                        'id' => $account->id,
                                                        'code' => $account->code,
                                                        'category_id' => $account->category_id,
                                                        'name' => $account->name,
                                                        'type' => $account->type,
                                                        'opening_balance' => $account->opening_balance,
                                                        'status' => $account->status,
                                                    ],
                                                    'destroy' => route('accounts.destroy', $account),
                                                    'has_transactions' => $account->transactions()->exists(),
                                                ]))" class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700" title="Actions">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-16 text-center">
                                        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                                            <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        </span>
                                        <p class="mt-4 text-sm font-medium text-gray-500">No accounts yet</p>
                                        <p class="mt-1 text-xs text-gray-400">Create your first account to start tracking money.</p>
                                        <button type="button" @click="openAddAccount()" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Add Account
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                </table>
            </div>
        </div>

        <!-- Action menu -->
        <div x-show="menuOpen" x-cloak @click.outside="menuOpen = false" @keydown.escape.window="menuOpen = false" :style="menuStyle" class="z-50 rounded-xl border border-gray-200 bg-white py-1.5 shadow-lg">
            <a :href="menuData.ledger" class="flex items-center gap-2.5 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-sky-50 hover:text-sky-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Ledger
            </a>
            <button type="button" @click="openEditAccount(menuData.edit); menuOpen = false" class="flex w-full items-center gap-2.5 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-sky-50 hover:text-sky-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                Edit
            </button>
            <template x-if="menuData.edit.status === 'Active'">
                <form :action="'{{ url('accounts') }}/' + menuData.edit.id + '/deactivate'" method="POST" @submit.prevent="if (confirm('Deactivate this account?')) { $el.submit(); }">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                        Deactivate
                    </button>
                </form>
            </template>
            <template x-if="menuData.edit.status === 'Inactive'">
                <form :action="'{{ url('accounts') }}/' + menuData.edit.id" method="POST" @submit.prevent="if (confirm('Reactivate this account?')) { $el.submit(); }">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" :value="menuData.edit.name">
                    <input type="hidden" name="type" :value="menuData.edit.type">
                    <input type="hidden" name="opening_balance" :value="menuData.edit.opening_balance">
                    <input type="hidden" name="status" value="Active">
                    <input type="hidden" name="category_id" :value="menuData.edit.category_id || ''">
                    <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2 text-sm font-medium text-emerald-600 transition hover:bg-emerald-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Reactivate
                    </button>
                </form>
            </template>
            <template x-if="menuData.edit.status === 'Active'">
                <form :action="menuData.destroy" method="POST" @submit.prevent="if (menuData.has_transactions) {
                    alert('This account has financial transactions and cannot be deleted. Deactivate it instead.');
                    menuOpen = false;
                } else if (confirm('Delete this account? This cannot be undone.')) {
                    $el.submit();
                }">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Delete
                    </button>
                </form>
            </template>
        </div>

        <!-- Add Account Category Modal -->
        <div x-show="showCategoryModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div @click="showCategoryModal = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-xl" @keydown.escape.window="showCategoryModal = false">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900">Add Account Category</h3>
                        <button type="button" @click="showCategoryModal = false" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('accounts.categories.store') }}" class="px-6 py-5">
                        @csrf
                        <div>
                            <div>
                                <x-input-label for="category_name" value="Category Name *" />
                                <x-text-input id="category_name" class="mt-1.5 block w-full !py-1.5" type="text" name="category_name" x-model="categoryForm.name" placeholder="e.g. Cash, Bank, Online Payment" required />
                                <x-input-error :messages="$errors->get('category_name')" class="mt-1" />
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="showCategoryModal = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add / Edit Account Modal -->
        <div x-show="showAccountModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div @click="showAccountModal = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-xl" @keydown.escape.window="showAccountModal = false">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900" x-text="modalMode === 'edit' ? 'Edit Account' : 'Add New Account'"></h3>
                        <button type="button" @click="showAccountModal = false" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form method="POST" :action="modalMode === 'edit' ? '/accounts/' + editingAccount.id : '{{ route('accounts.store') }}'" class="px-6 py-5">
                        @csrf
                        <input type="hidden" name="_method" value="PUT" :disabled="modalMode !== 'edit'" />

                        <div class="space-y-4">
                            <div>
                                <x-input-label for="account_category" value="Select Head (Category) *" />
                                <select id="account_category" name="category_id" x-model="accountForm.category_id" class="mt-1 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500" required>
                                    <option value="">Select Head</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="account_title" value="Account Title *" />
                                <x-text-input id="account_title" class="mt-1 block w-full !py-1.5" type="text" name="name" x-model="accountForm.name" placeholder="e.g. Main Cash" required />
                                <x-input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="account_type" value="Type" />
                                <select id="account_type" name="type" x-model="accountForm.type" class="mt-1 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                    @foreach (\App\Models\Account::TYPES as $acctType)
                                        <option value="{{ $acctType }}">{{ $acctType }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="account_opening" value="Opening Balance" />
                                <x-text-input id="account_opening" class="mt-1 block w-full !py-1.5" type="number" min="0" step="0.01" name="opening_balance" x-model="accountForm.opening_balance" />
                                <x-input-error :messages="$errors->get('opening_balance')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="account_status" value="Status" />
                                <select id="account_status" name="status" x-model="accountForm.status" class="mt-1 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                    @foreach (\App\Models\Account::STATUSES as $acctStatus)
                                        <option value="{{ $acctStatus }}">{{ $acctStatus }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-1" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="showAccountModal = false" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700" x-text="modalMode === 'edit' ? 'Update Account' : 'Save Account'"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>