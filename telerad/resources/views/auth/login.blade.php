<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-center text-gray-900">{{ __('TeleRad Login') }}</h1>
        <p class="mt-2 text-sm text-center text-gray-600">
            {{ __('Sign in to access the teleradiology system based on your role') }}
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="text-sm text-gray-600 text-center">
            <h3 class="font-medium text-gray-900 mb-2">{{ __('Available Roles') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">
                <div class="bg-gray-50 p-3 rounded-md">
                    <div class="text-base font-medium text-indigo-700 mb-1">{{ __('Hospital Admin') }}</div>
                    <p class="text-xs text-gray-600">{{ __('Manage radiologists and consultants for your hospital') }}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-md">
                    <div class="text-base font-medium text-indigo-700 mb-1">{{ __('Radiologist') }}</div>
                    <p class="text-xs text-gray-600">{{ __('Create reports for studies and view patient information') }}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-md">
                    <div class="text-base font-medium text-indigo-700 mb-1">{{ __('Consultant') }}</div>
                    <p class="text-xs text-gray-600">{{ __('View reports and provide expert consultation') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
