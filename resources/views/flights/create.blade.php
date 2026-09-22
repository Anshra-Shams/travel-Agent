<x-admin-layout>

    <div class="p-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">
                Add New Flight
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Add flight details, schedule, seats and pricing.
            </p>
        </div>

        <!-- Form Card -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <form action="{{ route('flights.store') }}" method="POST" class="p-6">
                @csrf

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
                        <div class="font-semibold text-red-700">
                            Please fix the following errors:
                        </div>

                        <ul class="mt-2 list-disc pl-5 text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Flight Information -->
                <div class="mb-6">
                    <h2 class="mb-4 text-lg font-semibold text-slate-800">
                        Flight Information
                    </h2>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <!-- Flight Number -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Flight Number
                            </label>

                            <input
                                type="text"
                                name="flight_number"
                                value="{{ old('flight_number') }}"
                                placeholder="e.g. PK-301"
                                required
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                            >
                        </div>

                        <!-- Airline -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Airline
                            </label>

                            <input
                                type="text"
                                name="airline"
                                value="{{ old('airline') }}"
                                placeholder="e.g. Pakistan International Airlines"
                                required
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                            >
                        </div>

                        <!-- From -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Departure From
                            </label>

                            <input
                                type="text"
                                name="departure_from"
                                value="{{ old('departure_from') }}"
                                placeholder="e.g. Karachi (KHI)"
                                required
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                            >
                        </div>

                        <!-- To -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Destination To
                            </label>

                            <input
                                type="text"
                                name="destination_to"
                                value="{{ old('destination_to') }}"
                                placeholder="e.g. Jeddah (JED)"
                                required
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                            >
                        </div>

                    </div>
                </div>


                <!-- Schedule -->
                <div class="mb-6 border-t border-slate-200 pt-6">

                    <h2 class="mb-4 text-lg font-semibold text-slate-800">
                        Flight Schedule
                    </h2>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <!-- Departure -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Departure Date & Time
                            </label>

                            <input
                                type="datetime-local"
                                name="departure_at"
                                value="{{ old('departure_at') }}"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                            >
                        </div>

                        <!-- Arrival -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Arrival Date & Time
                            </label>

                            <input
                                type="datetime-local"
                                name="arrival_at"
                                value="{{ old('arrival_at') }}"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                            >
                        </div>

                    </div>
                </div>


                <!-- Class & Seats -->
                <div class="mb-6 border-t border-slate-200 pt-6">

                    <h2 class="mb-4 text-lg font-semibold text-slate-800">
                        Class & Pricing
                    </h2>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                        <!-- Class -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Class
                            </label>

                            <select
                                name="class"
                                required
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                            >
                                <option value="Economy" {{ old('class') == 'Economy' ? 'selected' : '' }}>
                                    Economy
                                </option>

                                <option value="Business" {{ old('class') == 'Business' ? 'selected' : '' }}>
                                    Business
                                </option>
                            </select>
                        </div>

                        <!-- Seats -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Total Seats
                            </label>

                            <input
                                type="number"
                                name="total_seats"
                                value="{{ old('total_seats', 0) }}"
                                min="0"
                                required
                                placeholder="e.g. 180"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                            >
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Ticket Price (Rs.)
                            </label>

                            <input
                                type="number"
                                name="price"
                                value="{{ old('price', 0) }}"
                                min="0"
                                step="0.01"
                                required
                                placeholder="e.g. 85000"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                            >
                        </div>

                    </div>
                </div>


                <!-- Status -->
                <div class="mb-6 border-t border-slate-200 pt-6">

                    <h2 class="mb-4 text-lg font-semibold text-slate-800">
                        Flight Status
                    </h2>

                    <div class="max-w-md">

                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Status
                        </label>

                        <select
                            name="status"
                            required
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                        >
                            <option value="Scheduled" {{ old('status', 'Scheduled') == 'Scheduled' ? 'selected' : '' }}>
                                Scheduled
                            </option>

                            <option value="Confirmed" {{ old('status') == 'Confirmed' ? 'selected' : '' }}>
                                Confirmed
                            </option>

                            <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                            <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                        </select>

                    </div>

                </div>


                <!-- Notes -->
                <div class="mb-6 border-t border-slate-200 pt-6">

                    <h2 class="mb-4 text-lg font-semibold text-slate-800">
                        Additional Notes
                    </h2>

                    <textarea
                        name="notes"
                        rows="4"
                        placeholder="Enter any additional information about this flight..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-sky-500 focus:ring-sky-500"
                    >{{ old('notes') }}</textarea>

                </div>


                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">

                    <a
                        href="{{ route('flights.index') }}"
                        class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="rounded-lg bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700"
                    >
                        Save Flight
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-admin-layout>