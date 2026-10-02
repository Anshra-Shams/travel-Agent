<x-admin-layout title="Create Invoice">

    <div
        x-data="invoiceForm()"
        class="max-w-6xl mx-auto space-y-6"
    >

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Create Invoice
                </h1>

                <p class="text-sm text-slate-500 mt-1">
                    Create a professional invoice for a customer package.
                </p>
            </div>

            <a
                href="{{ route('invoices.index') }}"
                class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50"
            >
                Back
            </a>
        </div>


        @if ($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 p-4">
                <ul class="text-sm text-red-700 list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form
            method="POST"
            action="{{ route('invoices.store') }}"
            class="space-y-6"
        >

            @csrf


            {{-- Invoice Information --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                <h2 class="text-lg font-semibold text-slate-900 mb-5">
                    Invoice Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Customer --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Customer
                        </label>

                        <select
                            name="customer_id"
                            required
                            class="w-full rounded-lg border-slate-300"
                        >
                            <option value="">Select Customer</option>

                            @foreach ($customers as $customer)
                                <option
                                    value="{{ $customer->id }}"
                                    @selected(old('customer_id') == $customer->id)
                                >
                                    {{ $customer->name }}

                                    @if ($customer->phone)
                                        - {{ $customer->phone }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Service --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Service
                        </label>

                        <select
                            name="service_type_id"
                            x-model="serviceId"
                            @change="changeService()"
                            required
                            class="w-full rounded-lg border-slate-300"
                        >
                            <option value="">Select Service</option>

                            @foreach ($serviceTypes as $service)
                                <option value="{{ $service->id }}">
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Package --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Package
                        </label>

                        <select
                            name="package_id"
                            x-model="packageId"
                            @change="changePackage()"
                            required
                            class="w-full rounded-lg border-slate-300"
                        >
                            <option value="">Select Package</option>

                            <template x-for="pkg in packages" :key="pkg.id">
                                <option
                                    :value="pkg.id"
                                    x-text="pkg.name"
                                ></option>
                            </template>
                        </select>
                    </div>


                    {{-- Quantity --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Quantity
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            x-model.number="quantity"
                            @input="calculate()"
                            min="1"
                            value="{{ old('quantity', 1) }}"
                            required
                            class="w-full rounded-lg border-slate-300"
                        >
                    </div>

                </div>
            </div>


            {{-- Pricing --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                <h2 class="text-lg font-semibold text-slate-900 mb-5">
                    Pricing
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

                    {{-- Unit Price --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Unit Price
                        </label>

                        <input
                            type="text"
                            x-model="unitPrice"
                            readonly
                            class="w-full rounded-lg border-slate-300 bg-slate-50"
                        >
                    </div>


                    {{-- Subtotal --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Subtotal
                        </label>

                        <input
                            type="text"
                            x-model="subtotal"
                            readonly
                            class="w-full rounded-lg border-slate-300 bg-slate-50"
                        >
                    </div>


                    {{-- Discount --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Discount
                        </label>

                        <input
                            type="number"
                            name="discount"
                            x-model.number="discount"
                            @input="calculate()"
                            min="0"
                            step="0.01"
                            value="{{ old('discount', 0) }}"
                            class="w-full rounded-lg border-slate-300"
                        >
                    </div>


                    {{-- Grand Total --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Grand Total
                        </label>

                        <input
                            type="text"
                            x-model="grandTotal"
                            readonly
                            class="w-full rounded-lg border-slate-300 bg-slate-50 font-semibold"
                        >
                    </div>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">

                    {{-- Paid Amount --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Paid Amount
                        </label>

                        <input
                            type="number"
                            name="paid_amount"
                            x-model.number="paidAmount"
                            min="0"
                            step="0.01"
                            value="{{ old('paid_amount', 0) }}"
                            @input="calculate()"
                            class="w-full rounded-lg border-slate-300"
                        >
                    </div>


                    {{-- Remaining --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Remaining
                        </label>

                        <input
                            type="text"
                            x-model="remaining"
                            readonly
                            class="w-full rounded-lg border-slate-300 bg-slate-50 font-semibold"
                        >
                    </div>


                    {{-- Currency --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Currency
                        </label>

                        <select
                            name="currency"
                            class="w-full rounded-lg border-slate-300"
                        >
                            <option
                                value="PKR"
                                @selected(old('currency', 'PKR') === 'PKR')
                            >
                                PKR
                            </option>

                            <option
                                value="USD"
                                @selected(old('currency') === 'USD')
                            >
                                USD
                            </option>

                            <option
                                value="SAR"
                                @selected(old('currency') === 'SAR')
                            >
                                SAR
                            </option>
                        </select>
                    </div>

                </div>
            </div>


            {{-- Status & Notes --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                <h2 class="text-lg font-semibold text-slate-900 mb-5">
                    Status & Notes
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full rounded-lg border-slate-300"
                        >
                            <option
                                value="draft"
                                @selected(old('status', 'draft') === 'draft')
                            >
                                Draft
                            </option>

                            <option
                                value="sent"
                                @selected(old('status') === 'sent')
                            >
                                Sent
                            </option>

                            <option
                                value="partial"
                                @selected(old('status') === 'partial')
                            >
                                Partial
                            </option>

                            <option
                                value="paid"
                                @selected(old('status') === 'paid')
                            >
                                Paid
                            </option>

                            <option
                                value="cancelled"
                                @selected(old('status') === 'cancelled')
                            >
                                Cancelled
                            </option>
                        </select>
                    </div>


                    {{-- Issue Date --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Issue Date
                        </label>

                        <input
                            type="date"
                            name="issued_at"
                            value="{{ old('issued_at', now()->format('Y-m-d')) }}"
                            class="w-full rounded-lg border-slate-300"
                        >
                    </div>

                </div>


                {{-- Notes --}}
                <div class="mt-5">

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        rows="4"
                        class="w-full rounded-lg border-slate-300"
                        placeholder="Additional invoice notes..."
                    >{{ old('notes') }}</textarea>

                </div>

            </div>


            {{-- Buttons --}}
            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('invoices.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-700"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700"
                >
                    Create Invoice
                </button>

            </div>

        </form>


        <script>
            function invoiceForm() {

                const services = @js(
                    $serviceTypes->map(function ($service) {

                        return [
                            'id' => $service->id,
                            'name' => $service->name,

                            'packages' => $service->packages->map(function ($package) {

                                $price = (float) ($package->final_price ?? 0);

                                if ($price <= 0) {
                                    $price = (float) ($package->original_price ?? 0);
                                }

                                return [
                                    'id' => $package->id,
                                    'name' => $package->name,
                                    'price' => $price,
                                ];

                            })->values(),
                        ];

                    })->values()
                );


                return {

                    serviceId: '',
                    packageId: '',
                    packages: [],

                    quantity: 1,

                    unitPrice: 0,
                    subtotal: 0,
                    discount: 0,
                    grandTotal: 0,
                    paidAmount: 0,
                    remaining: 0,


                    changeService() {

                        const service = services.find(
                            item => String(item.id) === String(this.serviceId)
                        );

                        this.packages = service
                            ? service.packages
                            : [];

                        this.packageId = '';
                        this.unitPrice = 0;

                        this.calculate();
                    },


                    changePackage() {

                        const pkg = this.packages.find(
                            item => String(item.id) === String(this.packageId)
                        );

                        this.unitPrice = pkg
                            ? Number(pkg.price)
                            : 0;

                        this.calculate();
                    },


                    calculate() {

                        this.subtotal =
                            Number(this.unitPrice || 0) *
                            Number(this.quantity || 1);


                        this.grandTotal = Math.max(
                            0,
                            this.subtotal -
                            Number(this.discount || 0)
                        );


                        this.remaining = Math.max(
                            0,
                            this.grandTotal -
                            Number(this.paidAmount || 0)
                        );
                    }

                };
            }
        </script>

    </div>

</x-admin-layout>
