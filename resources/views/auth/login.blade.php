<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Heading -->
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900">Welcome back</h2>
        <p class="mt-1.5 text-sm text-gray-500">Sign in to your account to continue</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1.5 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1.5 block w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-sky-600 shadow-sm focus:ring-sky-500">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-sky-600 transition hover:text-sky-700">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Submit -->
        <div>
            <x-primary-button class="w-full">
                {{ __('Sign in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Footer link -->
    <p class="mt-6 text-center text-sm text-gray-500">
        {{ __("Don't have an account?") }}
        <a href="{{ route('register') }}" class="font-semibold text-sky-600 transition hover:text-sky-700">
            {{ __('Create one') }}
        </a>
    </p>
</x-guest-layout>
