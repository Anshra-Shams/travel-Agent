<x-admin-layout title="Package Details">

    <div class="max-w-6xl mx-auto py-8 px-4">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

            <div>
                <p class="text-sm text-gray-500">
                    {{ $serviceType->name }}
                </p>

                <h1 class="text-3xl font-bold text-gray-900">
                    {{ $package->name }}
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Package Details
                </p>
            </div>

            <div class="flex gap-3">

                <a href="{{ route('packages.index', $serviceType) }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Back
                </a>

                <a href="{{ route('packages.print', [$serviceType, $package]) }}"
                   target="_blank"
                   class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    🖨 Print Package
                </a>

            </div>

        </div>

        {{-- Package Overview --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                Package Overview
            </h2>

            @if($package->description)
                <p class="text-gray-600 mb-5">
                    {{ $package->description }}
                </p>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Duration</p>
                    <p class="font-semibold text-gray-900">
                        {{ $package->duration_days ?? '-' }} Days
                    </p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Departure</p>
                    <p class="font-semibold text-gray-900">
                        {{ $package->departure_date?->format('d M Y') ?? '-' }}
                    </p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Return</p>
                    <p class="font-semibold text-gray-900">
                        {{ $package->return_date?->format('d M Y') ?? '-' }}
                    </p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="font-semibold {{ $package->status === 'active' ? 'text-green-600' : 'text-red-600' }}">
                        {{ ucfirst($package->status) }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Hotel Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

            <h2 class="text-xl font-semibold text-gray-900 mb-5">
                Hotel Details
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <p class="text-sm text-gray-500">Makkah Hotel</p>
                    <p class="font-medium text-gray-900">
                        {{ $package->details['makkah_hotel'] ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Makkah Distance</p>
                    <p class="font-medium text-gray-900">
                        {{ $package->details['makkah_distance'] ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Madina Hotel</p>
                    <p class="font-medium text-gray-900">
                        {{ $package->details['madina_hotel'] ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Madina Distance</p>
                    <p class="font-medium text-gray-900">
                        {{ $package->details['madina_distance'] ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Room Type</p>
                    <p class="font-medium text-gray-900">
                        {{ $package->details['room_type'] ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Travel & Visa --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

            <h2 class="text-xl font-semibold text-gray-900 mb-5">
                Travel & Visa Details
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <p class="text-sm text-gray-500">Airline</p>
                    <p class="font-medium text-gray-900">
                        {{ $package->details['airline'] ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Visa</p>
                    <p class="font-medium text-gray-900">
                        {{ $package->details['visa'] ?? '-' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Flight Details</p>
                    <p class="font-medium text-gray-900 whitespace-pre-line">
                        {{ $package->details['flight_details'] ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Transport</p>
                    <p class="font-medium text-gray-900 whitespace-pre-line">
                        {{ $package->details['transport'] ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Insurance</p>
                    <p class="font-medium text-gray-900 whitespace-pre-line">
                        {{ $package->details['insurance'] ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Ziyarat & Meals --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

            <h2 class="text-xl font-semibold text-gray-900 mb-5">
                Ziyarat & Meals
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <p class="text-sm text-gray-500">Makkah Ziyarat</p>
                    <p class="font-medium text-gray-900 whitespace-pre-line">
                        {{ $package->details['makkah_ziyarat'] ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Madina Ziyarat</p>
                    <p class="font-medium text-gray-900 whitespace-pre-line">
                        {{ $package->details['madina_ziyarat'] ?? '-' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Meals</p>
                    <p class="font-medium text-gray-900 whitespace-pre-line">
                        {{ $package->details['meals'] ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Included / Excluded --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

            <h2 class="text-xl font-semibold text-gray-900 mb-5">
                Services
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <h3 class="font-semibold text-green-700 mb-2">
                        Included Services
                    </h3>

                    <p class="text-gray-700 whitespace-pre-line">
                        {{ $package->details['included_services'] ?? '-' }}
                    </p>
                </div>

                <div>
                    <h3 class="font-semibold text-red-700 mb-2">
                        Excluded Services
                    </h3>

                    <p class="text-gray-700 whitespace-pre-line">
                        {{ $package->details['excluded_services'] ?? '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Pricing --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">

            <h2 class="text-xl font-semibold text-gray-900 mb-5">
                Pricing
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                <div>
                    <p class="text-sm text-gray-500">Original Price</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $package->currency }}
                        {{ number_format($package->original_price ?? 0) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Discount</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $package->currency }}
                        {{ number_format($package->discount ?? 0) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Final Price</p>
                    <p class="text-2xl font-bold text-indigo-600">
                        {{ $package->currency }}
                        {{ number_format($package->final_price ?? 0) }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Terms --}}
        @if($package->terms)

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    Terms & Conditions
                </h2>

                <p class="text-gray-700 whitespace-pre-line">
                    {{ $package->terms }}
                </p>

            </div>

        @endif

    </div>

</x-admin-layout>