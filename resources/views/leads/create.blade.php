<x-admin-layout title="Add Lead" hide-footer="true" compact="true">
    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Add Lead</h1>
            <p class="text-xs text-gray-500">Create a new lead for your travel agency.</p>
        </div>
        <a href="{{ route('leads.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
        </a>
    </div>

    <div class="mt-3">
        @include('leads.partials.form', [
            'action' => route('leads.store'),
            'submitLabel' => 'Save Lead',
            'back' => route('leads.index'),
        ])
    </div>
</x-admin-layout>
