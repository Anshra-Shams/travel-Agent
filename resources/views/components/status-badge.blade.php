@props(['status'])

@php
    $colors = [
        'New' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'Contacted' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'Interested' => 'bg-violet-50 text-violet-700 ring-violet-200',
        'Quotation Sent' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'Converted' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'Not Interested' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'Follow-up' => 'bg-orange-50 text-orange-700 ring-orange-200',
    ];
    $color = $colors[$status] ?? 'bg-gray-50 text-gray-700 ring-gray-200';
@endphp

<span class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $color }}">
    {{ $status }}
</span>
