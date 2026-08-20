<x-admin-layout title="Create Service">
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Service</h1>
                <p class="mt-0.5 text-sm text-gray-500">Add a new service category to the system.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('service-types.store') }}" class="space-y-6">
        @csrf

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Service Details</h3>
            <p class="mt-0.5 text-sm text-gray-500">Fill in the information for this new service category.</p>

            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="name" value="Service Name" />
                    <x-text-input id="name" class="mt-1.5 block w-full" type="text" name="name" :value="old('name')" required placeholder="e.g. Airport Transfer, Travel Insurance" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="icon" value="Icon (Emoji)" />
                    <x-text-input id="icon" class="mt-1.5 block w-full" type="text" name="icon" :value="old('icon', '📋')" maxlength="10" placeholder="e.g. ✈️ 🛂 🏨" />
                    <x-input-error :messages="$errors->get('icon')" class="mt-1" />
                    <p class="mt-1 text-xs text-gray-400">Enter a single emoji to represent this service.</p>
                </div>
                <div class="sm:col-span-2">
                    <x-input-label for="description" value="Short Description" />
                    <x-text-input id="description" class="mt-1.5 block w-full" type="text" name="description" :value="old('description')" placeholder="e.g. Airport transfers and ground transport" />
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                        <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
            <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                Create Service
            </button>
        </div>
    </form>
</x-admin-layout>
