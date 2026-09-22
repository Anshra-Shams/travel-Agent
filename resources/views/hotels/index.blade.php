```blade
<x-admin-layout>

    <div class="min-h-screen bg-slate-50">

        <div class="p-6 lg:p-8">

            {{-- Header --}}
            <div class="mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.7"
                                      d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M15 9h.01M9 12h.01M15 12h.01"/>
                            </svg>
                        </div>

                        <div>
                            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                                Hotel Management
                            </h1>

                            <p class="mt-1 text-sm text-slate-500">
                                Manage hotels, rooms, pricing and accommodation details.
                            </p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('hotels.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 4v16m8-8H4"/>
                    </svg>

                    Add Hotel
                </a>

            </div>


            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">

                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>

                    {{ session('success') }}
                </div>
            @endif


            {{-- Statistics --}}
            <div class="mb-8 grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

                {{-- Total Hotels --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Total Hotels
                            </p>

                            <p class="mt-2 text-2xl font-bold text-slate-800">
                                {{ $hotels->count() }}
                            </p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-sky-100 text-sky-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.7"
                                      d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                            </svg>
                        </div>

                    </div>
                </div>


                {{-- Active Hotels --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Active Hotels
                            </p>

                            <p class="mt-2 text-2xl font-bold text-emerald-600">
                                {{ $hotels->where('status', 'Active')->count() }}
                            </p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.7"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>

                    </div>
                </div>


                {{-- Inactive Hotels --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Inactive Hotels
                            </p>

                            <p class="mt-2 text-2xl font-bold text-rose-600">
                                {{ $hotels->where('status', 'Inactive')->count() }}
                            </p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-100 text-rose-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.7"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>

                    </div>
                </div>


                {{-- Total Rooms --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Total Rooms
                            </p>

                            <p class="mt-2 text-2xl font-bold text-violet-600">
                                {{ $hotels->sum('total_rooms') }}
                            </p>
                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.7"
                                      d="M4 20V9a2 2 0 012-2h12a2 2 0 012 2v11M7 20v-5h10v5M8 11h.01M12 11h.01M16 11h.01"/>
                            </svg>
                        </div>

                    </div>
                </div>

            </div>


            {{-- Hotels Table --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                {{-- Table Header --}}
                <div class="flex flex-col gap-3 border-b border-slate-200 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Hotels
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Complete list of registered hotels.
                        </p>
                    </div>

                    <span class="inline-flex w-fit rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                        {{ $hotels->count() }} Records
                    </span>

                </div>


                @if($hotels->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-slate-200">

                            <thead class="bg-slate-50">

                                <tr>

                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                        Hotel
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                        Location
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                        Rooms
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                        Price / Night
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                        Status
                                    </th>

                                    <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-100 bg-white">

                                @foreach($hotels as $hotel)

                                    <tr class="transition hover:bg-slate-50">

                                        {{-- Hotel --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            <div class="flex items-center gap-3">

                                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="1.7"
                                                              d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                                                    </svg>
                                                </div>

                                                <div>
                                                    <div class="font-semibold text-slate-800">
                                                        {{ $hotel->hotel_name }}
                                                    </div>

                                                    <div class="mt-0.5 text-xs text-slate-500">
                                                        {{ $hotel->hotel_code }}
                                                    </div>
                                                </div>

                                            </div>

                                        </td>


                                        {{-- Location --}}
                                        <td class="px-6 py-5">

                                            <div class="font-medium text-slate-700">
                                                {{ $hotel->city }}
                                            </div>

                                            <div class="mt-0.5 text-xs text-slate-500">
                                                {{ $hotel->country }}
                                            </div>

                                        </td>


                                        {{-- Rooms --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            <span class="font-semibold text-slate-700">
                                                {{ $hotel->total_rooms }}
                                            </span>

                                            <span class="text-xs text-slate-500">
                                                rooms
                                            </span>

                                        </td>


                                        {{-- Price --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            <div class="font-semibold text-slate-800">
                                                Rs. {{ number_format((float) $hotel->price_per_night, 2) }}
                                            </div>

                                            <div class="text-xs text-slate-500">
                                                per night
                                            </div>

                                        </td>


                                        {{-- Status --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            @if($hotel->status === 'Active')

                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                    Active
                                                </span>

                                            @else

                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Actions --}}
                                        <td class="whitespace-nowrap px-6 py-5">

                                            <div class="flex items-center justify-end gap-2">

                                                <a href="{{ route('hotels.show', $hotel) }}"
                                                   class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-600">
                                                    View
                                                </a>

                                                <a href="{{ route('hotels.edit', $hotel) }}"
                                                   class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-600">
                                                    Edit
                                                </a>

                                                <form action="{{ route('hotels.destroy', $hotel) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this hotel?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600">
                                                        Delete
                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    {{-- Empty State --}}
                    <div class="px-6 py-16 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">

                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.5"
                                      d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                            </svg>

                        </div>

                        <h3 class="mt-5 text-lg font-bold text-slate-700">
                            No Hotels Added
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                            Start building your hotel inventory by adding your first hotel.
                        </p>

                        <a href="{{ route('hotels.create') }}"
                           class="mt-6 inline-flex items-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">

                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M12 4v16m8-8H4"/>
                            </svg>

                            Add First Hotel
                        </a>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-admin-layout>
```