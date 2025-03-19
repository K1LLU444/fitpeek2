<x-guest-layout>
    <div class="bg-gray-100 flex justify-center items-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
            <div class="flex justify-center mb-4">
                <x-application-logo class="h-16 w-auto ml-10" />
            </div>
            
            <h2 class="text-2xl font-bold text-center">Let's get started</h2>
            <p class="text-base text-center mb-6">Please register to login</p>
            
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                
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
                <div class="mt-4 relative">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" 
                        class="block mt-1 w-full placeholder-gray-600 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" 
                        placeholder="••••••••"
                        type="password"
                        name="password"
                        required autocomplete="new-password" />
                    <button type="button" onclick="togglePassword('password', 'togglePasswordIcon1')" class="absolute inset-y-0 right-3 top-8 text-gray-500">
                        <svg id="togglePasswordIcon1" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" />
                        </svg>
                    </button>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4 relative">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" 
                        class="block mt-1 w-full placeholder-gray-600 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" 
                        placeholder="••••••••"
                        type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                    <button type="button" onclick="togglePassword('password_confirmation', 'togglePasswordIcon2')" class="absolute inset-y-0 right-3 top-8 text-gray-500">
                        <svg id="togglePasswordIcon2" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" />
                        </svg>
                    </button>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex flex-col items-center mt-4">
                    <x-primary-button class="w-full rounded-3xl text-white transition duration-300 focus:outline-none">
                        {{ __('Create Account') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
