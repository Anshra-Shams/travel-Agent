<x-admin-layout title="Select Service Requirement">
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('quotations.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white p-2 text-gray-500 transition hover:bg-gray-50 hover:text-gray-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create Quotation</h1>
                <p class="mt-0.5 text-sm text-gray-500">Select a service requirement to create a quotation from.</p>
            </div>
        </div>
    </div>

    @if ($requirements->isEmpty())
        <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
                <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </span>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">No Requirements Available</h3>
            <p class="mt-2 text-sm text-gray-500">No services are ready for quotation yet.</p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Create Service Requirement
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($requirements as $req)
                <a href="{{ route('quotations.create', ['requirement' => $req->id]) }}"
                   class="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-sky-300 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                                {{ strtoupper(substr($req->customer->name, 0, 1)) }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $req->customer->name }}</p>
                                <p class="text-xs text-gray-400">{{ $req->customer->phone }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center whitespace-nowrap rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-200">{{ $req->service_type }}</span>
                    </div>
                    <div class="mt-4 space-y-2 text-xs text-gray-500">
                        @if ($req->destination)
                            <div class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $req->destination }}
                            </div>
                        @endif
                        @if ($req->travel_date)
                            <div class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ $req->travel_date->format('d M Y') }}
                            </div>
                        @endif
                        @if ($req->travelers)
                            <div class="flex items-center gap-2">
                                <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $req->travelers }} traveler(s)
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3">
                        <span class="text-xs text-gray-400">{{ $req->created_at->diffForHumans() }}</span>
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-sky-600 group-hover:text-sky-700">
                            Create Quotation
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-admin-layout>
