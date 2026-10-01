<x-admin-layout title="Packages">

    <div class="max-w-6xl mx-auto py-8 px-4">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $serviceType->name }} Packages
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Manage packages for this service.
                </p>
            </div>

            <a href="{{ route('packages.create', $serviceType) }}"
               class="inline-flex items-center px-4 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                + Add Package
            </a>

        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-5 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        {{-- Packages --}}
        @if($packages->count())

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                @foreach($packages as $package)

                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">

                        {{-- Package Header --}}
                        <div class="flex items-start justify-between gap-3">

                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">
                                    {{ $package->name }}
                                </h2>

                                <span class="inline-block mt-2 px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $package->status === 'active'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($package->status) }}
                                </span>
                            </div>

                        </div>

                        {{-- Description --}}
                        @if($package->description)
                            <p class="text-sm text-gray-600 mt-4">
                                {{ $package->description }}
                            </p>
                        @endif

                        {{-- Basic Details --}}
                        <div class="mt-5 space-y-2 text-sm">

                            @if($package->departure_date)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Departure</span>
                                    <span class="font-medium">
                                        {{ $package->departure_date->format('d M Y') }}
                                    </span>
                                </div>
                            @endif

                            @if($package->return_date)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Return</span>
                                    <span class="font-medium">
                                        {{ $package->return_date->format('d M Y') }}
                                    </span>
                                </div>
                            @endif

                            @if($package->duration_days)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Duration</span>
                                    <span class="font-medium">
                                        {{ $package->duration_days }} Days
                                    </span>
                                </div>
                            @endif

                        </div>

                        {{-- Price --}}
                        <div class="mt-5 pt-4 border-t">

                            <p class="text-xs text-gray-500">
                                Final Price
                            </p>

                            <p class="text-xl font-bold text-gray-900 mt-1">
                                {{ $package->currency }}
                                {{ number_format($package->final_price ?? 0, 0) }}
                            </p>

                        </div>

                        {{-- Actions --}}
                        <div class="mt-5 pt-4 border-t flex gap-2">

                            <a href="{{ route('packages.show', [$serviceType, $package]) }}"
                               class="flex-1 text-center px-3 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200">
                                View Details
                            </a>

                            <a href="{{ route('packages.print', [$serviceType, $package]) }}"
                               target="_blank"
                               class="flex-1 text-center px-3 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                                🖨 Print
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="bg-white border border-dashed border-gray-300 rounded-xl p-10 text-center">

                <h2 class="text-lg font-semibold text-gray-800">
                    No Packages Yet
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Create the first package for {{ $serviceType->name }}.
                </p>

                <a href="{{ route('packages.create', $serviceType) }}"
                   class="inline-block mt-5 px-5 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    Create Package
                </a>

            </div>

        @endif

    </div>

</x-admin-layout>