```blade
<x-admin-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Add Visa Application
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Create a new visa application for a customer.
                </p>
            </div>

            <a href="{{ route('visa.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                ← Back
            </a>

        </div>
    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Errors --}}
            @if ($errors->any())

                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg px-5 py-4">

                    <strong>Please fix the following errors:</strong>

                    <ul class="mt-2 list-disc list-inside text-sm">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <form action="{{ route('visa.store') }}" method="POST">

                @csrf


                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">


                    {{-- LEFT SIDE --}}
                    <div class="lg:col-span-2 space-y-6">


                        {{-- Customer & Visa Information --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                            <h3 class="text-lg font-bold text-gray-800 mb-5">
                                Customer & Visa Information
                            </h3>


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                                {{-- Customer --}}
                                <div>

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Customer <span class="text-red-500">*</span>
                                    </label>

                                    <select name="customer_id"
                                            required
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                                        <option value="">
                                            Select Customer
                                        </option>

                                        @foreach($customers as $customer)

                                            <option value="{{ $customer->id }}"
                                                {{ old('customer_id') == $customer->id ? 'selected' : '' }}>

                                                {{ $customer->name }}

                                                @if($customer->phone)
                                                    — {{ $customer->phone }}
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- Visa Type --}}
                                <div>

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Visa Type <span class="text-red-500">*</span>
                                    </label>

                                    <select name="visa_type"
                                            required
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                                        <option value="">
                                            Select Visa Type
                                        </option>

                                        <option value="Tourist" {{ old('visa_type') == 'Tourist' ? 'selected' : '' }}>
                                            Tourist
                                        </option>

                                        <option value="Business" {{ old('visa_type') == 'Business' ? 'selected' : '' }}>
                                            Business
                                        </option>

                                        <option value="Student" {{ old('visa_type') == 'Student' ? 'selected' : '' }}>
                                            Student
                                        </option>

                                        <option value="Work" {{ old('visa_type') == 'Work' ? 'selected' : '' }}>
                                            Work
                                        </option>

                                        <option value="Umrah" {{ old('visa_type') == 'Umrah' ? 'selected' : '' }}>
                                            Umrah
                                        </option>

                                        <option value="Other" {{ old('visa_type') == 'Other' ? 'selected' : '' }}>
                                            Other
                                        </option>

                                    </select>

                                </div>


                                {{-- Country --}}
                                <div>

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Country <span class="text-red-500">*</span>
                                    </label>

                                    <input type="text"
                                           name="country"
                                           value="{{ old('country') }}"
                                           placeholder="e.g. Saudi Arabia"
                                           required
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                                </div>


                                {{-- Application Number --}}
                                <div>

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Application Number
                                    </label>

                                    <input type="text"
                                           name="application_number"
                                           value="{{ old('application_number') }}"
                                           placeholder="e.g. VISA-2026-001"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                                </div>


                                {{-- Application Date --}}
                                <div>

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Application Date
                                    </label>

                                    <input type="date"
                                           name="application_date"
                                           value="{{ old('application_date') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                                </div>


                                {{-- Expiry Date --}}
                                <div>

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Expiry Date
                                    </label>

                                    <input type="date"
                                           name="expiry_date"
                                           value="{{ old('expiry_date') }}"
                                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                                </div>


                                {{-- Status --}}
                                <div class="md:col-span-2">

                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Visa Status <span class="text-red-500">*</span>
                                    </label>

                                    <select name="status"
                                            required
                                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

                                        <option value="Pending"
                                            {{ old('status', 'Pending') == 'Pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>

                                        <option value="Submitted"
                                            {{ old('status') == 'Submitted' ? 'selected' : '' }}>
                                            Submitted
                                        </option>

                                        <option value="Approved"
                                            {{ old('status') == 'Approved' ? 'selected' : '' }}>
                                            Approved
                                        </option>

                                        <option value="Rejected"
                                            {{ old('status') == 'Rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>

                                        <option value="Expired"
                                            {{ old('status') == 'Expired' ? 'selected' : '' }}>
                                            Expired
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>


                        {{-- Required Documents --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                            <h3 class="text-lg font-bold text-gray-800">
                                Required Documents
                            </h3>

                            <p class="text-sm text-gray-500 mt-1 mb-5">
                                Select the documents required for this visa application.
                            </p>


                            @php

                                $documents = [
                                    'Passport',
                                    'CNIC',
                                    'Passport Size Photos',
                                    'Bank Statement',
                                    'Travel Insurance',
                                    'Hotel Booking',
                                    'Flight Booking',
                                    'Invitation Letter',
                                ];

                                $oldDocuments = old('required_documents', []);

                            @endphp


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                                @foreach($documents as $document)

                                    <label class="flex items-center border border-gray-200 rounded-lg p-4 cursor-pointer hover:bg-gray-50 transition">

                                        <input type="checkbox"
                                               name="required_documents[]"
                                               value="{{ $document }}"
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                               {{ in_array($document, $oldDocuments) ? 'checked' : '' }}>

                                        <span class="ml-3 text-sm font-medium text-gray-700">
                                            {{ $document }}
                                        </span>

                                    </label>

                                @endforeach

                            </div>

                        </div>


                        {{-- Notes --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                            <h3 class="text-lg font-bold text-gray-800 mb-4">
                                Additional Notes
                            </h3>

                            <textarea name="notes"
                                      rows="5"
                                      placeholder="Enter additional information..."
                                      class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>

                        </div>


                    </div>


                    {{-- RIGHT SIDE --}}
                    <div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">

                            <h3 class="text-lg font-bold text-gray-800 mb-3">
                                Save Application
                            </h3>

                            <p class="text-sm text-gray-500 mb-6">
                                Check the information before saving the visa application.
                            </p>


                            <button type="submit"
                                    class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">

                                Save Visa Application

                            </button>


                            <a href="{{ route('visa.index') }}"
                               class="block text-center w-full mt-3 border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50">

                                Cancel

                            </a>

                        </div>

                    </div>


                </div>

            </form>

        </div>

    </div>

</x-admin-layout>
```