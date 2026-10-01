<x-admin-layout title="Create Package">

    <div class="max-w-5xl mx-auto py-8 px-4">

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

        <form action="{{ route('packages.store', $serviceType) }}"
              method="POST"
              class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">

            @csrf

            {{-- Package Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Package Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="e.g. 22 Days Umrah Package"
                       required
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>

                <textarea name="description"
                          rows="4"
                          placeholder="Enter package description..."
                          class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Departure Date
                    </label>

                    <input type="date"
                           name="departure_date"
                           value="{{ old('departure_date') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Return Date
                    </label>

                    <input type="date"
                           name="return_date"
                           value="{{ old('return_date') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Duration (Days)
                    </label>

                    <input type="number"
                           name="duration_days"
                           value="{{ old('duration_days') }}"
                           min="1"
                           placeholder="22"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

            </div>

            {{-- Pricing --}}
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Pricing
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Original Price
                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="original_price"
                               value="{{ old('original_price') }}"
                               placeholder="375000"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Discount
                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="discount"
                               value="{{ old('discount', 0) }}"
                               placeholder="35000"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Final Price
                        </label>

                        <input type="number"
                               step="0.01"
                               min="0"
                               name="final_price"
                               value="{{ old('final_price') }}"
                               placeholder="340000"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                </div>
            </div>

            {{-- Currency & Status --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Currency
                    </label>

                    <select name="currency"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                        <option value="PKR"
                            {{ old('currency', 'PKR') === 'PKR' ? 'selected' : '' }}>
                            PKR
                        </option>

                        <option value="USD"
                            {{ old('currency') === 'USD' ? 'selected' : '' }}>
                            USD
                        </option>

                        <option value="SAR"
                            {{ old('currency') === 'SAR' ? 'selected' : '' }}>
                            SAR
                        </option>

                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>

                    <select name="status"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                        <option value="active"
                            {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>
                </div>

            </div>

            {{-- Terms --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Terms & Conditions
                </label>

                <textarea name="terms"
                          rows="5"
                          placeholder="Enter package terms and conditions..."
                          class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('terms') }}</textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t">

                <a href="{{ route('packages.index', $serviceType) }}"
                   class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>

                <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    Create Package
                </button>

            </div>

        </form>

    </div>

</x-admin-layout>
{{-- Package Details --}}
<div class="border-t pt-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">
        Hotel Details
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Makkah Hotel
            </label>
            <input type="text"
                   name="makkah_hotel"
                   value="{{ old('makkah_hotel') }}"
                   placeholder="e.g. Makkah Hotel Name"
                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Makkah Distance
            </label>
            <input type="text"
                   name="makkah_distance"
                   value="{{ old('makkah_distance') }}"
                   placeholder="e.g. 500 Meter"
                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Madina Hotel
            </label>
            <input type="text"
                   name="madina_hotel"
                   value="{{ old('madina_hotel') }}"
                   placeholder="e.g. Madina Hotel Name"
                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Madina Distance
            </label>
            <input type="text"
                   name="madina_distance"
                   value="{{ old('madina_distance') }}"
                   placeholder="e.g. 400 Meter"
                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Room Type
            </label>
            <input type="text"
                   name="room_type"
                   value="{{ old('room_type') }}"
                   placeholder="e.g. Double Bedroom"
                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

    </div>
</div>

<div class="border-t pt-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">
        Travel & Visa Details
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Airline
            </label>
            <input type="text"
                   name="airline"
                   value="{{ old('airline') }}"
                   placeholder="e.g. Direct Airline"
                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Visa
            </label>
            <input type="text"
                   name="visa"
                   value="{{ old('visa') }}"
                   placeholder="e.g. Umrah Visa"
                   class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Flight Details
            </label>
            <textarea name="flight_details"
                      rows="3"
                      placeholder="Enter flight details..."
                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('flight_details') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Transport
            </label>
            <textarea name="transport"
                      rows="3"
                      placeholder="e.g. Full Transport"
                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('transport') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Insurance
            </label>
            <textarea name="insurance"
                      rows="3"
                      placeholder="Enter insurance details..."
                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('insurance') }}</textarea>
        </div>

    </div>
</div>

<div class="border-t pt-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">
        Ziyarat & Meals
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Makkah Ziyarat
            </label>
            <textarea name="makkah_ziyarat"
                      rows="3"
                      placeholder="Enter Makkah Ziyarat details..."
                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('makkah_ziyarat') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Madina Ziyarat
            </label>
            <textarea name="madina_ziyarat"
                      rows="3"
                      placeholder="Enter Madina Ziyarat details..."
                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('madina_ziyarat') }}</textarea>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Meals
            </label>
            <textarea name="meals"
                      rows="3"
                      placeholder="Enter meals details..."
                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('meals') }}</textarea>
        </div>

    </div>
</div>

<div class="border-t pt-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">
        Included & Excluded Services
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Included Services
            </label>
            <textarea name="included_services"
                      rows="5"
                      placeholder="Enter included services..."
                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('included_services') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Excluded Services
            </label>
            <textarea name="excluded_services"
                      rows="5"
                      placeholder="Enter excluded services..."
                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('excluded_services') }}</textarea>
        </div>

    </div>
</div>