<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Prowave') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen lg:flex">
            <!-- Brand panel -->
            <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-slate-900 p-12 lg:flex">
                <!-- Decorative gradient blobs -->
                <div class="pointer-events-none absolute -left-24 -top-24 h-96 w-96 rounded-full bg-sky-500/20 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 -right-16 h-96 w-96 rounded-full bg-indigo-600/20 blur-3xl"></div>
                <div class="pointer-events-none absolute bottom-1/4 left-1/3 h-64 w-64 rounded-full bg-cyan-400/10 blur-3xl"></div>

                <!-- Brand -->
                <div class="relative z-10 flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 text-white shadow-lg shadow-indigo-900/40">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a2 2 0 002 2h.97m0 0a2 2 0 104.06 0m-4.06 0h4.06m0 0h2.25m-2.25 0l1.76-5.28a1 1 0 01.95-.72h1.5a1 1 0 01.95.72L17.06 17m-2.06 0a2 2 0 104.06 0m-4.06 0h4.06M4 11l1-3h4l-1 3M21 9l-1.5 4.5a1 1 0 01-.95.72H18" />
                        </svg>
                    </span>
                    <span class="text-xl font-bold tracking-tight text-white">
                        Prow<span class="text-sky-400">ave</span>
                    </span>
                </div>

                <!-- Headline -->
                <div class="relative z-10">
                    <h1 class="text-3xl font-bold leading-tight text-white">
                        Manage your travel agency<br>in one place.
                    </h1>
                    <p class="mt-4 text-slate-400">
                        Leads, customers, bookings, payments and more — everything your agency needs, beautifully organized.
                    </p>

                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center gap-3 text-sm text-slate-300">
                            <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-sky-500/10 text-sky-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            Track leads, quotations and bookings
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-300">
                            <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-sky-500/10 text-sky-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            Manage customers, services and payments
                        </li>
                        <li class="flex items-center gap-3 text-sm text-slate-300">
                            <span class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-sky-500/10 text-sky-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            Follow-ups and documents at your fingertips
                        </li>
                    </ul>
                </div>

                <p class="relative z-10 text-sm text-slate-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Prowave') }}. All rights reserved.
                </p>
            </div>

            <!-- Form panel -->
            <div class="flex min-h-screen flex-1 items-center justify-center bg-gray-100 px-4 py-10 sm:px-6 lg:py-12">
                <div class="w-full max-w-md">
                    <!-- Brand (mobile only) -->
                    <div class="mb-8 flex flex-col items-center gap-3 lg:hidden">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-600 text-white shadow-lg shadow-indigo-500/30">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a2 2 0 002 2h.97m0 0a2 2 0 104.06 0m-4.06 0h4.06m0 0h2.25m-2.25 0l1.76-5.28a1 1 0 01.95-.72h1.5a1 1 0 01.95.72L17.06 17m-2.06 0a2 2 0 104.06 0m-4.06 0h4.06M4 11l1-3h4l-1 3M21 9l-1.5 4.5a1 1 0 01-.95.72H18" />
                            </svg>
                        </span>
                        <span class="text-xl font-bold tracking-tight text-gray-900">
                            Prow<span class="text-sky-600">ave</span>
                        </span>
                    </div>

                    <!-- Card -->
                    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm sm:p-10">
                        {{ $slot }}
                    </div>

                    <p class="mt-6 text-center text-xs text-gray-400 lg:hidden">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Prowave') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
