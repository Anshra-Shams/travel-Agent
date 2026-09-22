<x-admin-layout>

    <div class="p-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">
                Edit Visa Application
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Update visa application details and status.
            </p>
        </div>

        <!-- Form Card -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <form action="{{ route('visa.update', $visaApplication) }}" method="POST" class="p-6">

                @csrf
                @method('PUT')

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


                <!-- Customer & Visa Information -->
                <div class="mb-6">

                    <h2 class="mb-4 text-lg font-semibold text-slate-800">
                        Visa Information
                    </h2>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <!-- Customer -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Customer
                            </label>

                            <select
                                name="customer_id"
                                required
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                                @foreach($customers as $customer)

                                    <option
                                        value="{{ $customer->id }}"
                                        {{ old('customer_id', $visaApplication->customer_id) == $customer->id ? 'selected' : '' }}
                                    >
                                        {{ $customer->name }}
                                    </option>

                                @endforeach

                            </select>
                        </div>


                        <!-- Visa Type -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Visa Type
                            </label>

                            <input
                                type="text"
                                name="visa_type"
                                value="{{ old('visa_type', $visaApplication->visa_type) }}"
                                placeholder="e.g. Tourist Visa"
                                required
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>


                        <!-- Application Number -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Application Number
                            </label>

                            <input
                                type="text"
                                name="application_number"
                                value="{{ old('application_number', $visaApplication->application_number) }}"
                                placeholder="e.g. VISA-2026-001"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>


                        <!-- Status -->
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Status
                            </label>

                            <select
                                name="status"
                                required
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                                @foreach(['Pending', 'Submitted', 'Approved', 'Rejected', 'Expired'] as $status)

                                    <option
                                        value="{{ $status }}"
                                        {{ old('status', $visaApplication->status) == $status ? 'selected' : '' }}
                                    >
                                        {{ $status }}
                                    </option>

                                @endforeach

                            </select>
                        </div>

                    </div>

                </div>


                <!-- Dates -->
                <div class="mb-6 border-t border-slate-200 pt-6">

                    <h2 class="mb-4 text-lg font-semibold text-slate-800">
                        Application Dates
                    </h2>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        <!-- Application Date -->
                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Application Date
                            </label>

                            <input
                                type="date"
                                name="application_date"
                                value="{{ old('application_date', $visaApplication->application_date ? \Carbon\Carbon::parse($visaApplication->application_date)->format('Y-m-d') : '') }}"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        <!-- Expiry Date -->
                        <div>

                            <label class="mb-2 block text-sm font-semibold text-slate-700">
                                Expiry Date
                            </label>

                            <input
                                type="date"
                                name="expiry_date"
                                value="{{ old('expiry_date', $visaApplication->expiry_date ? \Carbon\Carbon::parse($visaApplication->expiry_date)->format('Y-m-d') : '') }}"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>

                    </div>

                </div>


                <!-- Required Documents -->
                <div class="mb-6 border-t border-slate-200 pt-6">

                    <h2 class="mb-4 text-lg font-semibold text-slate-800">
                        Required Documents
                    </h2>

                    <textarea
                        name="required_documents"
                        rows="4"
                        placeholder="Enter required documents..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('required_documents', is_array($visaApplication->required_documents) ? implode(', ', $visaApplication->required_documents) : $visaApplication->required_documents) }}</textarea>

                    <p class="mt-2 text-xs text-slate-500">
                        Example: Passport, CNIC, Photograph, Bank Statement
                    </p>

                </div>


                <!-- Notes -->
                <div class="mb-6 border-t border-slate-200 pt-6">

                    <h2 class="mb-4 text-lg font-semibold text-slate-800">
                        Notes
                    </h2>

                    <textarea
                        name="notes"
                        rows="4"
                        placeholder="Enter any additional notes..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('notes', $visaApplication->notes) }}</textarea>

                </div>


                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">

                    <a
                        href="{{ route('visa.index') }}"
                        class="rounded-lg bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
                    >
                        Update Visa
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-admin-layout>