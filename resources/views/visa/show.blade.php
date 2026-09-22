<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Visa Application Details
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    View complete visa application information.
                </p>
            </div>

            <div class="flex gap-2">

                <a href="{{ route('visa.edit', $visaApplication) }}"
                   class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Edit Application
                </a>

                <a href="{{ route('visa.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    ← Back
                </a>

            </div>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Main Details --}}
                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white shadow-sm rounded-lg p-6">

                        <div class="flex justify-between items-start mb-6">

                            <div>

                                <h3 class="text-2xl font-bold text-gray-800">
                                    {{ $visaApplication->visa_type }} Visa
                                </h3>

                                <p class="text-gray-500 mt-1">
                                    {{ $visaApplication->country }}
                                </p>

                            </div>

                            @php
                                $statusClass = match($visaApplication->status) {
                                    'Approved' => 'bg-green-100 text-green-800',
                                    'Rejected' => 'bg-red-100 text-red-800',
                                    'Expired' => 'bg-gray-200 text-gray-800',
                                    'Submitted' => 'bg-blue-100 text-blue-800',
                                    default => 'bg-yellow-100 text-yellow-800',
                                };
                            @endphp

                            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusClass }}">
                                {{ $visaApplication->status }}
                            </span>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <p class="text-sm text-gray-500">
                                    Customer
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">
                                    {{ $visaApplication->customer->name ?? 'N/A' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">
                                    Application Number
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">
                                    {{ $visaApplication->application_number ?? '—' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">
                                    Application Date
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">
                                    {{ $visaApplication->application_date?->format('d M Y') ?? '—' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">
                                    Expiry Date
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">
                                    {{ $visaApplication->expiry_date?->format('d M Y') ?? '—' }}
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- Required Documents --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">

                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            Required Documents
                        </h3>

                        <p class="text-sm text-gray-500 mb-5">
                            Documents required for this visa application.
                        </p>

                        @if(!empty($visaApplication->required_documents))

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                                @foreach($visaApplication->required_documents as $document)

                                    <div class="border rounded-lg p-4 flex items-center">

                                        <span class="w-7 h-7 flex items-center justify-center rounded-full bg-green-100 text-green-700 mr-3">
                                            ✓
                                        </span>

                                        <span class="text-gray-700">
                                            {{ $document }}
                                        </span>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <p class="text-gray-500">
                                No required documents recorded.
                            </p>

                        @endif

                    </div>


                    {{-- Notes --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">

                        <h3 class="text-lg font-semibold text-gray-800 mb-3">
                            Additional Notes
                        </h3>

                        @if($visaApplication->notes)

                            <p class="text-gray-700 whitespace-pre-line">
                                {{ $visaApplication->notes }}
                            </p>

                        @else

                            <p class="text-gray-500">
                                No additional notes.
                            </p>

                        @endif

                    </div>

                </div>


                {{-- Customer Side --}}
                <div class="space-y-6">

                    <div class="bg-white shadow-sm rounded-lg p-6">

                        <h3 class="text-lg font-semibold text-gray-800 mb-5">
                            Customer Information
                        </h3>

                        <div class="space-y-4">

                            <div>
                                <p class="text-sm text-gray-500">
                                    Name
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ $visaApplication->customer->name ?? 'N/A' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">
                                    Phone
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ $visaApplication->customer->phone ?? '—' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">
                                    Email
                                </p>

                                <p class="font-semibold text-gray-800">
                                    {{ $visaApplication->customer->email ?? '—' }}
                                </p>
                            </div>

                        </div>

                        @if($visaApplication->customer)

                            <a href="{{ route('customers.show', $visaApplication->customer) }}"
                               class="block text-center mt-6 w-full border border-indigo-600 text-indigo-600 py-2 rounded-md hover:bg-indigo-50">
                                View Customer Profile
                            </a>

                        @endif

                    </div>


                    {{-- Timeline --}}
                    <div class="bg-white shadow-sm rounded-lg p-6">

                        <h3 class="text-lg font-semibold text-gray-800 mb-5">
                            Application Timeline
                        </h3>

                        <div class="space-y-4">

                            <div>
                                <p class="text-sm text-gray-500">
                                    Created
                                </p>

                                <p class="font-medium text-gray-800">
                                    {{ $visaApplication->created_at?->format('d M Y, h:i A') ?? '—' }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500">
                                    Last Updated
                                </p>

                                <p class="font-medium text-gray-800">
                                    {{ $visaApplication->updated_at?->format('d M Y, h:i A') ?? '—' }}
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>