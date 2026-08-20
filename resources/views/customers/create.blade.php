<x-admin-layout title="Add Customer">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add Customer</h1>
        <p class="mt-1 text-sm text-gray-500">Create a new customer directly.</p>
    </div>

    @include('customers.partials.form', [
        'action' => route('customers.store'),
        'submitLabel' => 'Save Customer',
        'back' => route('customers.index'),
    ])
</x-admin-layout>
