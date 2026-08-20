<x-admin-layout title="Add Service">
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add Service Requirement</h1>
                <p class="mt-0.5 text-sm text-gray-500">
                    @if ($preselectedService)
                        Record <span class="font-medium text-gray-700">{{ $preselectedService }}</span> requirements for a customer.
                    @else
                        Record a service and its requirements for an existing customer.
                    @endif
                </p>
            </div>
        </div>
    </div>

    @include('services.partials.form', [
        'action' => route('services.store'),
        'submitLabel' => 'Save Service',
        'back' => route('services.index'),
        'selectedCustomerId' => $selectedCustomerId,
        'preselectedService' => $preselectedService,
    ])
</x-admin-layout>
