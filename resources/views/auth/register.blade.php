<x-guest-layout>
    <div class="bg-gray-100 flex justify-center items-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
            <!-- Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo_fitpeek.svg') }}" alt="FitPeek Logo" class="h-20 w-auto">
            </div>
            
            <h2 class="text-2xl font-bold text-center">Let's get started</h2>
            <p class="text-base text-center mb-6">Please register to login</p>
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Username')" />
                    <x-text-input 
                        id="name" 
                        class="block mt-1 w-full placeholder-gray-600 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" 
                        placeholder="Enter your username" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" 
                        class="block mt-1 w-full placeholder-gray-600 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" 
                        placeholder="Enter your gmail"
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" 
                        class="block mt-1 w-full placeholder-gray-600 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" 
                        placeholder="••••••••"
                        type="password"
                        name="password"
                        required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" 
                        class="block mt-1 w-full placeholder-gray-600 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" 
                        placeholder="••••••••"
                        type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex flex-col items-center mt-4">
                    <x-primary-button class="w-40 rounded-3xl text-white bg-[#F886B5] hover:bg-[#e075a3] transition duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#F886B5] dark:focus-visible:ring-white">
                        {{ __('Create Account') }}
                    </x-primary-button>
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-2" href="{{ route('login') }}">
                        {{ __('Sign In') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>