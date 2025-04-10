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
        <!-- Google Login Button -->
<div class="mt-6">
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">
                Or continue with
            </span>
        </div>
    </div>

    <a href="{{ route('google.login') }}" class="mt-4 w-full flex justify-center items-center py-3 px-4 rounded-md shadow-sm bg-white border border-gray-300 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        <span class="text-sm font-medium text-gray-700">Sign in with Google</span>
    </a>
</div>


        <!-- Login Button -->
        <x-primary-button class="auth-btn w-full flex justify-center">
            {{ __('Log in') }}
        </x-primary-button>
    </form>
</x-guest-layout>