<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }} | {{ config('app.name', 'Prowave') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-gray-100 lg:flex">
            <!-- Mobile overlay -->
            <div
                x-show="sidebarOpen"
                x-transition:enter="transition-opacity ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"
                style="display: none"
                @click="sidebarOpen = false">
            </div>

            <!-- Sidebar -->
            <aside
                class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col bg-slate-900 transition-transform duration-200 ease-in-out lg:translate-x-0"
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                @include('layouts.partials.sidebar')
            </aside>

            <!-- Main column -->
            <div class="flex min-h-screen min-w-0 flex-1 flex-col lg:pl-64">
                @include('layouts.partials.navbar')

                <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    @include('layouts.partials.flash')
                    {{ $slot }}
                </main>

                <footer class="border-t border-gray-200 bg-white px-4 py-4 text-center text-xs text-gray-400 sm:px-6">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Prowave') }}. All rights reserved.
                </footer>
            </div>
        </div>
    </body>
</html>
