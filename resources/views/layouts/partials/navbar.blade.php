<header class="sticky top-0 z-20 flex h-16 shrink-0 items-center gap-3 border-b border-gray-200 bg-white px-4 sm:px-6">
    <!-- Mobile hamburger -->
    <button type="button" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 lg:hidden" @click="sidebarOpen = true">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Search -->
    <div class="relative hidden w-full max-w-md flex-1 sm:block">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </span>
        <input type="search" placeholder="Search leads, customers, bookings..." class="w-full rounded-lg border-0 bg-gray-100 py-2.5 pl-10 pr-4 text-sm text-gray-900 placeholder-gray-400 ring-1 ring-transparent transition focus:bg-white focus:ring-2 focus:ring-sky-500" />
    </div>

    <div class="ml-auto flex items-center gap-2">
        <!-- Notifications -->
        <button type="button" class="relative rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute right-1.5 top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white">3</span>
        </button>

        <!-- User dropdown -->
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button type="button" class="flex items-center gap-2.5 rounded-lg p-1.5 transition hover:bg-gray-100">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-indigo-600 text-xs font-bold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                    <span class="hidden text-sm font-medium text-gray-700 sm:block">{{ Auth::user()->name }}</span>
                    <svg class="hidden h-4 w-4 text-gray-400 sm:block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="border-b border-gray-100 px-4 py-3">
                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="truncate text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>
                <x-dropdown-link :href="route('dashboard')">
                    {{ __('Dashboard') }}
                </x-dropdown-link>
                <x-dropdown-link :href="route('settings.index')">
                    {{ __('Settings') }}
                </x-dropdown-link>
                <div class="border-t border-gray-100"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
