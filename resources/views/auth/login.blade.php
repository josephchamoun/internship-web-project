<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center text-lg font-medium text-pink-600" :status="session('status')" />

    <!-- Logo -->
    <div class="text-center">
        <div class="flex justify-center items-center w-24 h-24 mx-auto mb-4 bg-white rounded-full shadow-lg">
            <x-application-logo class="w-16 h-16 text-pink-500" />
        </div>
        <h2 class="mt-4 text-3xl font-bold text-gray-900">
            Welcome to <br>
            <span class="bg-gradient-to-r from-pink-500 to-blue-500 bg-clip-text text-transparent">
                Epic Toy Store
            </span>
        </h2>
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
        @csrf

        <!-- Email Input -->
        <div>
            <x-input-label for="email" :value="('Email')" class="text-gray-700 font-medium" />
            <x-text-input 
                id="email" 
                class="input-field mt-1 w-full py-3 px-4" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username" 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-600" />
        </div>

        <!-- Password Input -->
        <div>
            <x-input-label for="password" :value="('Password')" class="text-gray-700 font-medium" />
            <x-text-input 
                id="password" 
                class="input-field mt-1 w-full py-3 px-4"
                type="password"
                name="password"
                required 
                autocomplete="current-password" 
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-600" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="rounded border-gray-300 text-pink-500 focus:ring-pink-500" 
                    name="remember"
                >
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="forgot-link text-sm font-medium" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <x-primary-button class="auth-btn w-full flex justify-center">
            {{ __('Log in') }}
        </x-primary-button>
    </form>
</x-guest-layout>