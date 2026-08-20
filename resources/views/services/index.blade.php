<x-admin-layout title="Services">
    <!-- Page header -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Services</h1>
            <p class="mt-1 text-sm text-gray-500">Browse travel services and manage customer requirements.</p>
        </div>
        <a href="{{ route('service-types.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Service
        </a>
    </div>

    <!-- Service cards grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($results as $item)
            @php
                $type = $item['serviceType'];
                $meta = $item['colors'];
                $topCustomers = $item['customers']->take(3);
                $extraCount  = max(0, $item['customerCount'] - 3);
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
                        <a href="{{ route('service-types.edit', $type) }}" title="Edit service" class="rounded p-0.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                    </div>
                    <p class="mt-0.5 text-xs text-gray-500">{{ $type->description }}</p>
                </div>

                <!-- Customer preview -->
                <div class="flex-1 px-5 pb-3">
                    @if ($topCustomers->isEmpty())
                        <p class="text-xs text-gray-400">No customers yet.</p>
                    @else
                        <ul class="space-y-1.5">
                            @foreach ($topCustomers as $customer)
                                <li class="flex items-center gap-2">
                                    <span class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-gray-100 text-[10px] font-bold text-gray-500">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </span>
                                    <a href="{{ route('customers.show', $customer) }}" class="truncate text-xs font-medium text-gray-700 hover:text-sky-600">{{ $customer->name }}</a>
                                </li>
                            @endforeach
                            @if ($extraCount > 0)
                                <li class="pl-8 text-[11px] font-medium text-gray-400">+{{ $extraCount }} more {{ Str::plural('customer', $extraCount) }}</li>
                            @endif
                        </ul>
                    @endif
                </div>

                <!-- Requirement count -->
                <div class="mx-5 mb-3 border-t border-gray-100 pt-3">
                    <p class="text-xs text-gray-500">
                        <span class="font-semibold text-gray-700">{{ $item['requirementCount'] }}</span>
                        {{ Str::plural('requirement record', $item['requirementCount']) }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 border-t border-gray-100 px-5 py-3">
                    <a href="{{ route('services.type', $type->name) }}" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        View Customers
                    </a>
                    <a href="{{ route('services.create', ['service' => $type->name]) }}" class="inline-flex items-center justify-center gap-1.5 rounded-lg {{ $meta['badge'] }} px-3 py-2 text-xs font-semibold ring-1 ring-inset {{ $meta['ring'] }} transition hover:opacity-80">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                <a href="{{ route('service-types.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Service
                </a>
            </div>
        @endforelse
    </div>
</x-admin-layout>
