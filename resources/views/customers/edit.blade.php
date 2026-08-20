<x-admin-layout title="Edit Customer">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Customer</h1>
            <p class="mt-1 text-sm text-gray-500">Update the details of <span class="font-medium text-gray-700">{{ $customer->name }}</span>.</p>
        </div>
        <a href="{{ route('customers.show', $customer) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            View Profile
        </a>
    </div>

    @include('customers.partials.form', [
        'customer' => $customer,
        'action' => route('customers.update', $customer),
        'method' => 'PATCH',
        'submitLabel' => 'Update Customer',
        'back' => route('customers.show', $customer),
    ])
</x-admin-layout>
