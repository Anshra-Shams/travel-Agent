<x-admin-layout :title="'Edit ' . $serviceType->name">
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Service</h1>
                <p class="mt-0.5 text-sm text-gray-500">
                    Update <span class="font-medium text-gray-700">{{ $serviceType->name }}</span> service details.
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('service-types.update', $serviceType) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-gray-900">Service Details</h3>
            <p class="mt-0.5 text-sm text-gray-500">Update the information for this service category.</p>

            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="name" value="Service Name" />
                    <x-text-input id="name" class="mt-1.5 block w-full" type="text" name="name" :value="old('name', $serviceType->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="icon" value="Icon (Emoji)" />
                    <x-text-input id="icon" class="mt-1.5 block w-full" type="text" name="icon" :value="old('icon', $serviceType->icon)" maxlength="10" />
                    <x-input-error :messages="$errors->get('icon')" class="mt-1" />
                </div>
                <div class="sm:col-span-2">
                    <x-input-label for="description" value="Short Description" />
                    <x-text-input id="description" class="mt-1.5 block w-full" type="text" name="description" :value="old('description', $serviceType->description)" />
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="active" @selected(old('status', $serviceType->status) === 'active')>Active</option>
                        <option value="inactive" @selected(old('status', $serviceType->status) === 'inactive')>Inactive</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="POST" action="{{ route('service-types.destroy', $serviceType) }}" onsubmit="return confirm('Are you sure you want to delete this service type? This will NOT delete existing requirement records.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-rose-300 bg-white px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Service
                </button>
            </form>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center">
                <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
                    Update Service
                </button>
            </div>
        </div>
    </form>
</x-admin-layout>
