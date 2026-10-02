<x-admin-layout title="Create Package">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Package</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-5xl mx-auto py-8 px-4">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            Create Package
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Create a new package under
            <span class="font-semibold text-gray-700">
                {{ $serviceType->name }}
            </span>
        </p>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
            <h3 class="font-semibold text-red-700 mb-2">
                Please fix the following errors:
            </h3>

            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- MAIN FORM --}}
    <form
        action="{{ route('packages.store', $serviceType) }}"
        method="POST"
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
    >

        @csrf

        {{-- ========================= --}}
        {{-- PACKAGE INFORMATION --}}
        {{-- ========================= --}}

        <div class="mb-8">

            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Package Information
            </h2>

            {{-- Package Name --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Package Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="e.g. 22 Days Umrah Package"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                >

                @error('name')
                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    placeholder="Enter package description..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('description') }}</textarea>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Departure Date
                    </label>

                    <input
                        type="date"
                        name="departure_date"
                        value="{{ old('departure_date') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Return Date
                    </label>

                    <input
                        type="date"
                        name="return_date"
                        value="{{ old('return_date') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Duration (Days)
                    </label>

                    <input
                        type="number"
                        name="duration_days"
                        value="{{ old('duration_days') }}"
                        min="1"
                        placeholder="22"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

            </div>
        </div>


        {{-- ========================= --}}
        {{-- HOTEL DETAILS --}}
        {{-- ========================= --}}

        <div class="border-t border-gray-200 pt-8 mb-8">

            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Hotel Details
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Makkah Hotel --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Makkah Hotel
                    </label>

                    <input
                        type="text"
                        name="makkah_hotel"
                        value="{{ old('makkah_hotel') }}"
                        placeholder="e.g. Anjum Hotel"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Makkah Distance --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Makkah Distance
                    </label>

                    <input
                        type="text"
                        name="makkah_distance"
                        value="{{ old('makkah_distance') }}"
                        placeholder="e.g. 500 Meter"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Madina Hotel --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Madina Hotel
                    </label>

                    <input
                        type="text"
                        name="madina_hotel"
                        value="{{ old('madina_hotel') }}"
                        placeholder="e.g. Madinah Hotel"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Madina Distance --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Madina Distance
                    </label>

                    <input
                        type="text"
                        name="madina_distance"
                        value="{{ old('madina_distance') }}"
                        placeholder="e.g. 400 Meter"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Room Type --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Room Type
                    </label>

                    <input
                        type="text"
                        name="room_type"
                        value="{{ old('room_type') }}"
                        placeholder="e.g. Double Bedroom"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

            </div>
        </div>


        {{-- ========================= --}}
        {{-- TRAVEL & VISA --}}
        {{-- ========================= --}}

        <div class="border-t border-gray-200 pt-8 mb-8">

            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Travel & Visa Details
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Airline --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Airline
                    </label>

                    <input
                        type="text"
                        name="airline"
                        value="{{ old('airline') }}"
                        placeholder="e.g. Saudi Airlines"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Visa --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Visa
                    </label>

                    <input
                        type="text"
                        name="visa"
                        value="{{ old('visa') }}"
                        placeholder="e.g. Umrah Visa"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Flight Details --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Flight Details
                    </label>

                    <textarea
                        name="flight_details"
                        rows="3"
                        placeholder="Enter flight details..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('flight_details') }}</textarea>
                </div>

                {{-- Transport --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Transport
                    </label>

                    <textarea
                        name="transport"
                        rows="3"
                        placeholder="e.g. Full Transport"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('transport') }}</textarea>
                </div>

                {{-- Insurance --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Insurance
                    </label>

                    <textarea
                        name="insurance"
                        rows="3"
                        placeholder="e.g. Insurance Included"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('insurance') }}</textarea>
                </div>

            </div>
        </div>


        {{-- ========================= --}}
        {{-- ZIYARAT & MEALS --}}
        {{-- ========================= --}}

        <div class="border-t border-gray-200 pt-8 mb-8">

            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Ziyarat & Meals
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Makkah Ziyarat --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Makkah Ziyarat
                    </label>

                    <textarea
                        name="makkah_ziyarat"
                        rows="4"
                        placeholder="Enter Makkah Ziyarat details..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('makkah_ziyarat') }}</textarea>
                </div>

                {{-- Madina Ziyarat --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Madina Ziyarat
                    </label>

                    <textarea
                        name="madina_ziyarat"
                        rows="4"
                        placeholder="Enter Madina Ziyarat details..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('madina_ziyarat') }}</textarea>
                </div>

                {{-- Meals --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Meals
                    </label>

                    <textarea
                        name="meals"
                        rows="4"
                        placeholder="Enter meals details..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('meals') }}</textarea>
                </div>

            </div>
        </div>


        {{-- ========================= --}}
        {{-- INCLUDED / EXCLUDED --}}
        {{-- ========================= --}}

        <div class="border-t border-gray-200 pt-8 mb-8">

            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Included & Excluded Services
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Included --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Included Services
                    </label>

                    <textarea
                        name="included_services"
                        rows="6"
                        placeholder="Enter included services..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('included_services') }}</textarea>
                </div>

                {{-- Excluded --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Excluded Services
                    </label>

                    <textarea
                        name="excluded_services"
                        rows="6"
                        placeholder="Enter excluded services..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('excluded_services') }}</textarea>
                </div>

            </div>
        </div>


        {{-- ========================= --}}
        {{-- PRICING --}}
        {{-- ========================= --}}

        <div class="border-t border-gray-200 pt-8 mb-8">

            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                Pricing
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- Original --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Original Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="original_price"
                        value="{{ old('original_price') }}"
                        placeholder="375000"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Discount --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Discount
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="discount"
                        value="{{ old('discount', 0) }}"
                        placeholder="35000"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                {{-- Final --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Final Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="final_price"
                        value="{{ old('final_price') }}"
                        placeholder="340000"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

            </div>
        </div>


        {{-- ========================= --}}
        {{-- CURRENCY & STATUS --}}
        {{-- ========================= --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">

            {{-- Currency --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Currency
                </label>

                <select
                    name="currency"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="PKR" @selected(old('currency', 'PKR') === 'PKR')>
                        PKR
                    </option>

                    <option value="USD" @selected(old('currency') === 'USD')>
                        USD
                    </option>

                    <option value="SAR" @selected(old('currency') === 'SAR')>
                        SAR
                    </option>
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="active" @selected(old('status', 'active') === 'active')>
                        Active
                    </option>

                    <option value="inactive" @selected(old('status') === 'inactive')>
                        Inactive
                    </option>
                </select>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- TERMS --}}
        {{-- ========================= --}}

        <div class="mb-8">

            <label class="block text-sm font-medium text-gray-700 mb-2">
                Terms & Conditions
            </label>

            <textarea
                name="terms"
                rows="5"
                placeholder="Enter package terms and conditions..."
                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-indigo-500"
            >{{ old('terms') }}</textarea>

        </div>


        {{-- ========================= --}}
        {{-- BUTTONS --}}
        {{-- ========================= --}}

        <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">

            <a
                href="{{ route('packages.index', $serviceType) }}"
                class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"
            >
                Create Package
            </button>

        </div>

    </form>

</div>

<footer class="text-center text-sm text-gray-500 py-6">
    © {{ date('Y') }} Laravel. All rights reserved.
</footer>

</body>
</html>

</x-admin-layout>