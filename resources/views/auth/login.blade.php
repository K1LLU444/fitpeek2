<x-guest-layout>
    <div class="flex items-center justify-center">
        <div class="bg-white shadow-xl rounded-lg p-8 w-full max-w-md m-auto mt-20">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <div class="flex justify-center mb-4">
                <x-application-logo class="h-16 w-auto ml-10" />
            </div>

            <h2 class="text-2xl font-bold text-center mb-6">Login to your Account</h2>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full shadow-inner-xl placeholder-gray-600 px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"  
                                  placeholder="Enter your email" 
                                  type="email" name="email" 
                                  :value="old('email')" 
                                  required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4 relative">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full shadow-inner placeholder-gray-600 px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"  
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                    <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" class="absolute inset-y-0 right-3 top-8 text-gray-500">
                        <svg id="togglePasswordIcon" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z" />
                        </svg>
                    </button>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col justify-center mt-6">
                    <x-primary-button class="rounded-3xl text-white bg-[#F886B5] hover:bg-[#e075a3] transition duration-300 px-6 py-2">
                        {{ __('Log In') }}
                    </x-primary-button>
                    <a class="text-center text-sm text-gray-600 hover:underline hover:text-gray-900 mt-2" href="{{ route('register') }}">
                        {{ __('Create an account') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
