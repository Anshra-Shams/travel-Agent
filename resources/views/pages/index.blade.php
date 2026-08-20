<x-admin-layout :title="$title">
    <!-- Page header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        <p class="mt-1 text-sm text-gray-500">This module is under construction.</p>
    </div>

    <!-- Placeholder -->
    <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center">
        <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </span>
        <h2 class="mt-4 text-lg font-semibold text-gray-900">{{ $title }}</h2>
        <p class="mx-auto mt-1 max-w-md text-sm text-gray-500">
            The {{ strtolower($title) }} module will be added soon. Please check back later.
        </p>
    </div>
</x-admin-layout>
