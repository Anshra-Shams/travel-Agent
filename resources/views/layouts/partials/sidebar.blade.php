@php
    $navItems = [
        ['label' => 'Dashboard',   'route' => 'dashboard',     'icon' => 'home'],
        ['label' => 'Leads',       'route' => 'leads.index',   'icon' => 'user-plus'],
        ['label' => 'Customers',   'route' => 'customers.index', 'icon' => 'users'],
        ['label' => 'Services',    'route' => 'services.index', 'icon' => 'briefcase'],
        ['label' => 'Quotations',  'route' => 'quotations.index', 'icon' => 'document-text'],
        ['label' => 'Bookings',    'route' => 'bookings.index', 'icon' => 'calendar'],
        ['label' => 'Payments',    'route' => 'payments.index', 'icon' => 'banknotes'],
        ['label' => 'Documents',   'route' => 'documents.index', 'icon' => 'folder'],
        ['label' => 'Follow-ups',  'route' => 'follow-ups.index', 'icon' => 'clock'],
        ['label' => 'Reports',     'route' => 'reports.index',  'icon' => 'chart'],
        ['label' => 'Settings',    'route' => 'settings.index', 'icon' => 'cog'],
    ];

    $icons = [
        'home'         => 'M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h3a1 1 0 001-1V10',
        'user-plus'    => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
        'users'        => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'briefcase'    => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'document-text'=> 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'calendar'     => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'banknotes'    => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'folder'       => 'M3 7a2 2 0 012-2h4l2 2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V7z',
        'clock'        => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'chart'        => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'cog'          => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'logout'       => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z',
    ];
@endphp

<!-- Brand -->
<div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-800 px-4">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-sky-500 to-indigo-600 text-white shadow-lg shadow-indigo-900/40">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a2 2 0 002 2h.97m0 0a2 2 0 104.06 0m-4.06 0h4.06m0 0h2.25m-2.25 0l1.76-5.28a1 1 0 01.95-.72h1.5a1 1 0 01.95.72L17.06 17m-2.06 0a2 2 0 104.06 0m-4.06 0h4.06M4 11l1-3h4l-1 3M21 9l-1.5 4.5a1 1 0 01-.95.72H18" />
            </svg>
        </span>
        <span class="text-lg font-bold tracking-tight text-white">
            Prow<span class="text-sky-400">ave</span>
        </span>
    </a>

    <button type="button" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-800 hover:text-white lg:hidden" @click="sidebarOpen = false">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<!-- Navigation -->
<nav class="admin-sidebar flex-1 space-y-1 overflow-y-auto px-3 py-4">
    @foreach ($navItems as $item)
        @php $active = request()->routeIs($item['route']); @endphp
        <a href="{{ route($item['route']) }}" title="{{ $item['label'] }}"
           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                  {{ $active ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="h-5 w-5 flex-shrink-0 {{ $active ? 'text-sky-400' : 'text-slate-400 group-hover:text-sky-400' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{!! $icons[$item['icon']] !!}" />
            </svg>
            {{ $item['label'] }}
        </a>
    @endforeach
</nav>

<!-- Logout -->
<div class="shrink-0 border-t border-slate-800 p-3">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-rose-600/10 hover:text-rose-400">
            <svg class="h-5 w-5 flex-shrink-0 text-slate-400 group-hover:text-rose-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{!! $icons['logout'] !!}" />
            </svg>
            Logout
        </button>
    </form>
</div>
