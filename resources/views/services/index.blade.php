<x-admin-layout title="Services">
    @php
        $editTypes = collect($results)->map(fn ($r) => $r['serviceType'])->keyBy('id');
        $editId = old('edit_id') ? (int) old('edit_id') : null;
        $editData = ($editId !== null && isset($editTypes[$editId]))
            ? $editTypes[$editId]->only(['id', 'name', 'icon', 'description', 'amount', 'status'])
            : null;
        $isEditError = $editId !== null;
        if ($isEditError && $editData) {
            $editData['name'] = old('name', $editData['name']);
            $editData['description'] = old('description', $editData['description']);
            $editData['amount'] = old('amount', $editData['amount'] !== null ? $editData['amount'] : '');
            $editData['status'] = old('status', $editData['status']);
        }
    @endphp
    <div x-data="{
        showAddModal: @js($errors->any() && !$isEditError),
        editData: @js($editData),
        editForm: null,
        init() {
            if (this.editData) {
                this.editForm = { ...this.editData };
            }
        },
        openEdit(svc) {
            this.editData = svc;
            this.editForm = { ...svc };
        },
        closeEdit() {
            this.editForm = null;
        }
    }">
        <!-- Page header -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Services</h1>
                <p class="mt-1 text-sm text-gray-500">Browse travel services and manage customer requirements.</p>
            </div>
            <button type="button" @click="showAddModal = true" class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Service
            </button>
        </div>

        <!-- Service cards grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($results as $item)
                @php
                    $type = $item['serviceType'];
                    $meta = $item['colors'];
                @endphp

                <div class="group relative flex flex-col rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
                    <!-- Icon + type name -->
                    <div class="p-5 pb-3">
                        <div class="flex items-start justify-between">
                            <span class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl {{ $meta['icon_bg'] }} text-2xl">
                                {{ $type->icon }}
                            </span>
                            <span class="inline-flex items-center rounded-full {{ $meta['badge'] }} px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $meta['ring'] }}">
                                {{ $item['customerCount'] }} {{ Str::plural('customer', $item['customerCount']) }}
                            </span>
                        </div>
                        <div class="mt-3 flex items-center gap-2">
                            <h2 class="text-base font-bold text-gray-900">{{ $type->name }}</h2>
                        </div>
                        <p class="mt-0.5 text-xs text-gray-500">{{ $type->description }}</p>
                        @if ($type->amount !== null)
                            <p class="mt-2.5 text-base font-bold tracking-tight text-emerald-600">
                                Rs. <span class="tabular-nums">{{ number_format((float) $type->amount, 0) }}</span>
                            </p>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="mt-auto flex items-center gap-1.5 border-t border-gray-100 px-3 py-2.5">
                        <a href="{{ route('services.type', $type->name) }}" class="inline-flex min-w-0 flex-1 items-center justify-center gap-1 rounded-lg border border-gray-200 bg-white px-2 py-1.5 text-[11px] font-semibold text-gray-700 transition hover:bg-gray-50">
                            <svg class="h-3 w-3 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate">View Customers</span>
                        </a>
                        <button type="button" @click="openEdit({ id: {{ $type->id }}, name: @js($type->name), icon: @js($type->icon), description: @js($type->description), amount: @js($type->amount), status: @js($type->status) })" title="Edit service" class="inline-flex shrink-0 items-center justify-center gap-1 rounded-lg border border-gray-200 bg-white px-2 py-1.5 text-[11px] font-semibold text-gray-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </button>
                        <form method="POST" action="{{ route('service-types.destroy', $type) }}" onsubmit="return confirm('Delete &quot;{{ $type->name }}&quot;? Customer records will NOT be deleted.')" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Delete service" class="inline-flex items-center justify-center gap-1 rounded-lg border border-gray-200 bg-white px-2 py-1.5 text-[11px] font-semibold text-gray-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </form>
                        <a href="{{ route('services.create', ['service' => $type->name]) }}" class="inline-flex shrink-0 items-center justify-center gap-1 rounded-lg px-2 py-1.5 text-[11px] font-semibold ring-1 ring-inset transition hover:opacity-80 {{ $meta['badge'] }} {{ $meta['ring'] }}">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Add
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-3xl">📋</span>
                    <p class="mt-4 text-sm font-medium text-gray-500">No services found</p>
                    <p class="mt-1 text-xs text-gray-400">Create your first service category to get started.</p>
                    <button type="button" @click="showAddModal = true" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Service
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Add Service Modal -->
        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div @click="showAddModal = false" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-xl" @keydown.escape.window="showAddModal = false">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900">Add New Service</h3>
                        <button type="button" @click="showAddModal = false" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <form method="POST" action="{{ route('service-types.store') }}" class="px-6 py-5">
                        @csrf

                        <div>
                            <x-input-label for="name" value="Service Name *" />
                            <x-text-input id="name" class="mt-1.5 block w-full !py-1.5" type="text" name="name" :value="old('name')" placeholder="Enter service name" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div class="mt-5">
                            <x-input-label for="description" value="Short Description *" />
                            <x-text-input id="description" class="mt-1.5 block w-full !py-1.5" type="text" name="description" :value="old('description')" placeholder="Enter a short description about the service" required />
                            <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        </div>

                        <div class="mt-5">
                            <x-input-label for="amount" value="Service Amount" />
                            <x-text-input id="amount" class="mt-1.5 block w-full !py-1.5" type="number" name="amount" :value="old('amount')" step="0.01" min="0" placeholder="e.g. 150000" />
                            <x-input-error :messages="$errors->get('amount')" class="mt-1" />
                        </div>

                        <div class="mt-5">
                            <x-input-label for="status" value="Status *" />
                            <select id="status" name="status" class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', 'active') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-1" />
                        </div>

                        <!-- Modal footer -->
                        <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                            <button type="button" @click="showAddModal = false" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                                Save Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Service Modal -->
        <div x-show="editForm !== null" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div @click="closeEdit()" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-xl" @keydown.escape.window="closeEdit()">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h3 class="text-lg font-bold text-gray-900">Edit Service</h3>
                        <button type="button" @click="closeEdit()" class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form method="POST" x-bind:action="'/service-types/' + editForm.id" class="px-6 py-5">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="edit_id" x-bind:value="editForm.id">

                        <div>
                            <x-input-label for="edit_name" value="Service Name *" />
                            <input id="edit_name" type="text" name="name" x-model="editForm.name" required placeholder="Enter service name" class="mt-1.5 block w-full !py-1.5 rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" />
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div class="mt-5">
                            <x-input-label for="edit_description" value="Short Description *" />
                            <input id="edit_description" type="text" name="description" x-model="editForm.description" required placeholder="Enter a short description about the service" class="mt-1.5 block w-full !py-1.5 rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" />
                            <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        </div>

                        <div class="mt-5">
                            <x-input-label for="edit_amount" value="Service Amount" />
                            <input id="edit_amount" type="number" name="amount" x-model.number="editForm.amount" step="0.01" min="0" placeholder="e.g. 150000" class="mt-1.5 block w-full !py-1.5 rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500" />
                            <x-input-error :messages="$errors->get('amount')" class="mt-1" />
                        </div>

                        <div class="mt-5">
                            <x-input-label for="edit_status" value="Status *" />
                            <select id="edit_status" name="status" x-model="editForm.status" class="mt-1.5 block w-full rounded-lg border-gray-300 !py-1.5 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                                @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-1" />
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                            <button type="button" @click="closeEdit()" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                                Update Service
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>