```blade
<x-admin-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Visa Management
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Manage visa applications, status, expiry dates and required documents.
            </p>
        </div>
    </x-slot>

    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-5 rounded-lg bg-green-50 border border-green-200 px-5 py-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-5 py-4 text-red-700">

                    <strong>Please fix the following errors:</strong>

                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif

            {{-- Page Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Visa Applications
                    </h1>

                    <p class="text-gray-500 mt-1">
                        Track customers' visa applications and expiry information.
                    </p>
                </div>

                <a href="{{ route('visa.create') }}"
                   class="inline-flex items-center justify-center px-5 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition shadow-sm">

                    <span class="mr-2 text-lg">+</span>
                    Add Visa Application

                </a>

            </div>

            {{-- Visa Table --}}
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">

                <div class="p-6">

                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            <thead>

                                <tr class="border-b border-gray-200">

                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                        #
                                    </th>

                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Customer
                                    </th>

                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Visa Type
                                    </th>

                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Country
                                    </th>

                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Application No.
                                    </th>

                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Expiry Date
                                    </th>

                                    <th class="px-4 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                        Status
                                    </th>

                                    <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @forelse($visaApplications as $visa)

                                    <tr class="hover:bg-gray-50 transition">

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ $visa->id }}
                                        </td>

                                        <td class="px-4 py-4">

                                            <div class="font-semibold text-gray-800">
                                                {{ $visa->customer->name ?? 'N/A' }}
                                            </div>

                                            @if($visa->customer?->phone)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $visa->customer->phone }}
                                                </div>
                                            @endif

                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            {{ $visa->visa_type }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            {{ $visa->country }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ $visa->application_number ?? '—' }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            {{ $visa->expiry_date?->format('d M Y') ?? '—' }}
                                        </td>

                                        <td class="px-4 py-4">

                                            @php

                                                $statusClass = match($visa->status) {

                                                    'Approved' =>
                                                        'bg-green-100 text-green-700',

                                                    'Rejected' =>
                                                        'bg-red-100 text-red-700',

                                                    'Expired' =>
                                                        'bg-gray-200 text-gray-700',

                                                    'Submitted' =>
                                                        'bg-blue-100 text-blue-700',

                                                    default =>
                                                        'bg-yellow-100 text-yellow-700',

                                                };

                                            @endphp

                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                                {{ $visa->status }}
                                            </span>

                                        </td>

                                        <td class="px-4 py-4">

                                            <div class="flex justify-center items-center gap-3">

                                                <a href="{{ route('visa.show', $visa) }}"
                                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    View
                                                </a>

                                                <a href="{{ route('visa.edit', $visa) }}"
                                                   class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                                    Edit
                                                </a>

                                                <form action="{{ route('visa.destroy', $visa) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this visa application?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                        Delete
                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="8" class="px-6 py-16 text-center">

                                            <div class="text-gray-500">

                                                <div class="text-5xl mb-4">
                                                    🛂
                                                </div>

                                                <h3 class="text-lg font-bold text-gray-800">
                                                    No Visa Applications
                                                </h3>

                                                <p class="text-sm mt-2 mb-5">
                                                    No visa applications have been added yet.
                                                </p>

                                                <a href="{{ route('visa.create') }}"
                                                   class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700">

                                                    + Add Visa Application

                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    {{-- Pagination --}}
                    @if($visaApplications->hasPages())

                        <div class="mt-6 border-t pt-5">
                            {{ $visaApplications->links() }}
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-admin-layout>
```