<x-admin-layout>

    <div class="p-6">

        <!-- Header -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Flight Management
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Manage airline flights, schedules, seats and prices.
                </p>
            </div>

            <a href="{{ route('flights.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">

                + Add Flight

            </a>

        </div>


        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif


        <!-- Flights Table -->
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Flight
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Airline
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Route
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Departure
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Class
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Seats
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Price
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse($flights as $flight)

                            <tr class="transition hover:bg-slate-50">

                                <!-- Flight Number -->
                                <td class="whitespace-nowrap px-5 py-4">

                                    <div class="font-semibold text-slate-800">
                                        {{ $flight->flight_number }}
                                    </div>

                                </td>


                                <!-- Airline -->
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    {{ $flight->airline }}
                                </td>


                                <!-- Route -->
                                <td class="whitespace-nowrap px-5 py-4">

                                    <div class="flex items-center gap-2 text-sm">

                                        <span class="font-medium text-slate-700">
                                            {{ $flight->departure_from }}
                                        </span>

                                        <span class="text-slate-400">
                                            →
                                        </span>

                                        <span class="font-medium text-slate-700">
                                            {{ $flight->destination_to }}
                                        </span>

                                    </div>

                                </td>


                                <!-- Departure -->
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">

                                    @if($flight->departure_at)
                                        {{ $flight->departure_at->format('d M Y, h:i A') }}
                                    @else
                                        —
                                    @endif

                                </td>


                                <!-- Class -->
                                <td class="whitespace-nowrap px-5 py-4">

                                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                                        {{ $flight->class }}
                                    </span>

                                </td>


                                <!-- Seats -->
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    {{ $flight->total_seats }}
                                </td>


                                <!-- Price -->
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-slate-700">
                                    Rs. {{ number_format($flight->price, 2) }}
                                </td>


                                <!-- Status -->
                                <td class="whitespace-nowrap px-5 py-4">

                                    @if($flight->status === 'Scheduled')

                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                            Scheduled
                                        </span>

                                    @elseif($flight->status === 'Confirmed')

                                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                            Confirmed
                                        </span>

                                    @elseif($flight->status === 'Cancelled')

                                        <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            Completed
                                        </span>

                                    @endif

                                </td>


                                <!-- Actions -->
                                <td class="whitespace-nowrap px-5 py-4 text-right">

                                    <div class="flex justify-end gap-2">

                                        <a href="{{ route('flights.show', $flight) }}"
                                           class="rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200">
                                            View
                                        </a>

                                        <a href="{{ route('flights.edit', $flight) }}"
                                           class="rounded-lg bg-sky-50 px-3 py-2 text-xs font-semibold text-sky-700 hover:bg-sky-100">
                                            Edit
                                        </a>

                                        <form action="{{ route('flights.destroy', $flight) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this flight?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">
                                                Delete
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="px-6 py-12 text-center">

                                    <div class="text-4xl">
                                        ✈️
                                    </div>

                                    <h3 class="mt-3 text-lg font-semibold text-slate-700">
                                        No flights found
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Add your first flight to start managing flight schedules.
                                    </p>

                                    <a href="{{ route('flights.create') }}"
                                       class="mt-4 inline-flex rounded-lg bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-sky-700">
                                        + Add Flight
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-admin-layout>