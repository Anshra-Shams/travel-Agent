@php
    $isEdit = true;
    $q = $quotation;
    $requirement = $quotation->serviceRequirement;
@endphp
<x-admin-layout title="Edit Quotation {{ $quotation->quotation_number }}">
    <div class="no-print mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('quotations.show', $quotation) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit {{ $quotation->quotation_number }}</h1>
                <p class="mt-0.5 text-sm text-gray-500">Update quotation details and line items.</p>
            </div>
            <div class="ml-auto flex items-center gap-2">
                <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Preview
                </button>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="no-print mb-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('quotations.partials.form', [
        'action' => route('quotations.update', $quotation),
        'method' => 'PUT',
        'submitLabel' => 'Update Quotation',
        'back' => route('quotations.show', $quotation),
    ])
</x-admin-layout>
