<x-admin-layout title="Add Lead">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add Lead</h1>
        <p class="mt-1 text-sm text-gray-500">Create a new lead for your travel agency.</p>
    </div>

    @include('leads.partials.form', [
        'action' => route('leads.store'),
        'submitLabel' => 'Save Lead',
        'back' => route('leads.index'),
    ])
</x-admin-layout>
