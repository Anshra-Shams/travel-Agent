<x-admin-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Hotel Details
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    View complete hotel information
                </p>
            </div>

            <div class="flex gap-3">

                <a href="{{ route('hotels.edit', $hotel) }}"
                   class="px-5 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg">
                    Edit Hotel
                </a>

                <a href="{{ route('hotels.index') }}"
                   class="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg">
                    ← Back
                </a>

            </div>

        </div>
    </x-slot>

    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Hotel Header --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

                    <div class="flex items-center gap-4">

                        <div class="w-16 h-16 rounded-xl bg-purple-100 flex items-center justify-center">

                            <svg class="w-8 h-8 text-purple-600"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M15 9h.01M9 12h.01M15 12h.01"/>

                            </svg>

                        </div>

                        <div>

                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ $hotel->hotel_name }}
                            </h3>

                            <p class="text-gray-500 mt-1">
                                Hotel Code: {{ $hotel->hotel_code }}
                            </p>

                        </div>

                    </div>

                    @if($hotel->status === 'Active')

                        <span class="inline-flex w-fit px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-700">
                            Active
                        </span>

                    @else

                        <span class="inline-flex w-fit px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-600">
                            Inactive
                        </span>

                    @endif

                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Information --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Location --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                        <h3 class="text-lg font-bold text-gray-800 mb-5">
                            Location Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    City
                                </p>

                                <p class="mt-2 font-semibold text-gray-800">
                                    {{ $hotel->city }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    Country
                                </p>

                                <p class="mt-2 font-semibold text-gray-800">
                                    {{ $hotel->country }}
                                </p>
                            </div>

                            <div class="md:col-span-2 bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    Address
                                </p>

                                <p class="mt-2 text-gray-800">
                                    {{ $hotel->address ?? '—' }}
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- Contact --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                        <h3 class="text-lg font-bold text-gray-800 mb-5">
                            Contact Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    Contact Person
                                </p>

                                <p class="mt-2 font-semibold text-gray-800">
                                    {{ $hotel->contact_person ?? '—' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    Phone
                                </p>

                                <p class="mt-2 font-semibold text-gray-800">
                                    {{ $hotel->phone ?? '—' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    Email
                                </p>

                                <p class="mt-2 font-semibold text-gray-800 break-words">
                                    {{ $hotel->email ?? '—' }}
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- Room Details --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                        <h3 class="text-lg font-bold text-gray-800 mb-5">
                            Room & Pricing
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    Room Type
                                </p>

                                <p class="mt-2 font-semibold text-gray-800">
                                    {{ $hotel->room_type ?? '—' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    Total Rooms
                                </p>

                                <p class="mt-2 font-semibold text-gray-800">
                                    {{ $hotel->total_rooms }}
                                </p>
                            </div>

                            <div class="bg-purple-50 rounded-lg border border-purple-100 p-4">
                                <p class="text-xs font-bold text-purple-600 uppercase">
                                    Price Per Night
                                </p>

                                <p class="mt-2 text-xl font-bold text-purple-700">
                                    Rs. {{ number_format($hotel->price_per_night, 2) }}
                                </p>
                            </div>

                            <div class="md:col-span-3 bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    Meal Plan
                                </p>

                                <p class="mt-2 font-semibold text-gray-800">
                                    {{ $hotel->meal_plan ?? '—' }}
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- Schedule --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                        <h3 class="text-lg font-bold text-gray-800 mb-5">
                            Check-in & Check-out
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    Check-in
                                </p>

                                <p class="mt-2 font-semibold text-gray-800">
                                    {{ $hotel->check_in_time ? date('h:i A', strtotime($hotel->check_in_time)) : '—' }}
                                </p>
                            </div>

                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                <p class="text-xs font-bold text-gray-500 uppercase">
                                    Check-out
                                </p>

                                <p class="mt-2 font-semibold text-gray-800">
                                    {{ $hotel->check_out_time ? date('h:i A', strtotime($hotel->check_out_time)) : '—' }}
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- Notes --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            Additional Notes
                        </h3>

                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-700 leading-7">
                            {{ $hotel->notes ?? 'No additional notes available.' }}
                        </div>

                    </div>

                </div>

                {{-- Right Side --}}
                <div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">

                        <h3 class="text-lg font-bold text-gray-800 mb-5">
                            Hotel Summary
                        </h3>

                        <div class="space-y-4">

                            <div class="flex justify-between border-b border-gray-100 pb-3">
                                <span class="text-gray-500">
                                    Hotel Code
                                </span>

                                <span class="font-semibold text-gray-800">
                                    {{ $hotel->hotel_code }}
                                </span>
                            </div>

                            <div class="flex justify-between border-b border-gray-100 pb-3">
                                <span class="text-gray-500">
                                    City
                                </span>

                                <span class="font-semibold text-gray-800">
                                    {{ $hotel->city }}
                                </span>
                            </div>

                            <div class="flex justify-between border-b border-gray-100 pb-3">
                                <span class="text-gray-500">
                                    Country
                                </span>

                                <span class="font-semibold text-gray-800">
                                    {{ $hotel->country }}
                                </span>
                            </div>

                            <div class="flex justify-between border-b border-gray-100 pb-3">
                                <span class="text-gray-500">
                                    Rooms
                                </span>

                                <span class="font-semibold text-gray-800">
                                    {{ $hotel->total_rooms }}
                                </span>
                            </div>

                            <div class="pt-2">
                                <p class="text-sm text-gray-500">
                                    Price Per Night
                                </p>

                                <p class="text-2xl font-bold text-purple-600 mt-1">
                                    Rs. {{ number_format($hotel->price_per_night, 2) }}
                                </p>
                            </div>

                        </div>

                        <a href="{{ route('hotels.edit', $hotel) }}"
                           class="block text-center w-full mt-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-lg">
                            Edit Hotel
                        </a>

                        <form action="{{ route('hotels.destroy', $hotel) }}"
                              method="POST"
                              class="mt-3"
                              onsubmit="return confirm('Are you sure you want to delete this hotel?');">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="w-full py-3 bg-red-50 hover:bg-red-100 text-red-600 font-semibold rounded-lg">
                                Delete Hotel
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-admin-layout>