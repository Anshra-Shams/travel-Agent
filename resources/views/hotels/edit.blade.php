<x-admin-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Edit Hotel
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Update hotel information, rooms, pricing and availability
                </p>
            </div>

            <a href="{{ route('hotels.index') }}"
               class="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-5">
                    <h3 class="font-bold text-red-700 mb-2">
                        Please fix the following errors:
                    </h3>

                    <ul class="list-disc list-inside text-sm text-red-600">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('hotels.update', $hotel) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <div class="lg:col-span-2 space-y-6">

                        {{-- Hotel Information --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                            <h3 class="text-lg font-bold text-gray-800 mb-1">
                                Hotel Information
                            </h3>

                            <p class="text-sm text-gray-500 mb-6">
                                Basic information about the hotel
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Hotel Name *
                                    </label>

                                    <input type="text"
                                           name="hotel_name"
                                           value="{{ old('hotel_name', $hotel->hotel_name) }}"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Hotel Code *
                                    </label>

                                    <input type="text"
                                           name="hotel_code"
                                           value="{{ old('hotel_code', $hotel->hotel_code) }}"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        City *
                                    </label>

                                    <input type="text"
                                           name="city"
                                           value="{{ old('city', $hotel->city) }}"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Country *
                                    </label>

                                    <input type="text"
                                           name="country"
                                           value="{{ old('country', $hotel->country) }}"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Address
                                    </label>

                                    <textarea name="address"
                                              rows="3"
                                              class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">{{ old('address', $hotel->address) }}</textarea>
                                </div>

                            </div>
                        </div>

                        {{-- Contact Information --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                            <h3 class="text-lg font-bold text-gray-800 mb-1">
                                Contact Information
                            </h3>

                            <p class="text-sm text-gray-500 mb-6">
                                Hotel contact and communication details
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Contact Person
                                    </label>

                                    <input type="text"
                                           name="contact_person"
                                           value="{{ old('contact_person', $hotel->contact_person) }}"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Phone
                                    </label>

                                    <input type="text"
                                           name="phone"
                                           value="{{ old('phone', $hotel->phone) }}"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email
                                    </label>

                                    <input type="email"
                                           name="email"
                                           value="{{ old('email', $hotel->email) }}"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">
                                </div>

                            </div>
                        </div>

                        {{-- Room & Pricing --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                            <h3 class="text-lg font-bold text-gray-800 mb-1">
                                Room & Pricing
                            </h3>

                            <p class="text-sm text-gray-500 mb-6">
                                Configure room and accommodation details
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Room Type
                                    </label>

                                    <select name="room_type"
                                            class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">

                                        <option value="">Select Room Type</option>

                                        @foreach(['Single', 'Double', 'Triple', 'Quad', 'Family', 'Suite'] as $type)
                                            <option value="{{ $type }}"
                                                {{ old('room_type', $hotel->room_type) == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Total Rooms *
                                    </label>

                                    <input type="number"
                                           name="total_rooms"
                                           value="{{ old('total_rooms', $hotel->total_rooms) }}"
                                           min="0"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Price Per Night *
                                    </label>

                                    <input type="number"
                                           name="price_per_night"
                                           value="{{ old('price_per_night', $hotel->price_per_night) }}"
                                           min="0"
                                           step="0.01"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Meal Plan
                                    </label>

                                    <select name="meal_plan"
                                            class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">

                                        <option value="">Select Meal Plan</option>

                                        @foreach(['Room Only', 'Breakfast', 'Half Board', 'Full Board'] as $meal)
                                            <option value="{{ $meal }}"
                                                {{ old('meal_plan', $hotel->meal_plan) == $meal ? 'selected' : '' }}>
                                                {{ $meal }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                            </div>
                        </div>

                        {{-- Schedule --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                            <h3 class="text-lg font-bold text-gray-800 mb-1">
                                Check-in & Check-out
                            </h3>

                            <p class="text-sm text-gray-500 mb-6">
                                Hotel arrival and departure timings
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Check-in Time
                                    </label>

                                    <input type="time"
                                           name="check_in_time"
                                           value="{{ old('check_in_time', $hotel->check_in_time) }}"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Check-out Time
                                    </label>

                                    <input type="time"
                                           name="check_out_time"
                                           value="{{ old('check_out_time', $hotel->check_out_time) }}"
                                           class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">
                                </div>

                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                            <h3 class="text-lg font-bold text-gray-800 mb-1">
                                Additional Notes
                            </h3>

                            <textarea name="notes"
                                      rows="5"
                                      placeholder="Add any additional information..."
                                      class="w-full mt-4 rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-purple-500 focus:border-purple-500">{{ old('notes', $hotel->notes) }}</textarea>

                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="space-y-6">

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">

                            <h3 class="text-lg font-bold text-gray-800">
                                Update Hotel
                            </h3>

                            <p class="text-sm text-gray-500 mt-1 mb-6">
                                Save the updated hotel information.
                            </p>

                            <button type="submit"
                                    class="w-full py-3 px-4 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg shadow-sm transition">
                                Update Hotel
                            </button>

                            <a href="{{ route('hotels.index') }}"
                               class="block text-center w-full mt-3 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg">
                                Cancel
                            </a>

                        </div>

                        <div class="bg-purple-50 border border-purple-100 rounded-xl p-6">

                            <h3 class="font-bold text-purple-800 mb-2">
                                Current Status
                            </h3>

                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold
                                {{ $hotel->status === 'Active'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-gray-100 text-gray-600' }}">
                                {{ $hotel->status }}
                            </span>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

</x-admin-layout>