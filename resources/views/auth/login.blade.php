<x-guest-layout>
    <div class="flex items-center justify-center">
        <div class="bg-white shadow-xl rounded-lg p-8 w-full max-w-md m-auto mt-20">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <div class="flex justify-center">
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
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full shadow-inner placeholder-gray-600 px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"  
                                    placeholder="••••••••"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="mr-2">
                        <label for="remember_me" class="text-sm text-gray-600">Remember me</label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="underline text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center mt-6">
                    <x-primary-button class="rounded-3xl text-white bg-[#F886B5] hover:bg-[#e075a3] transition duration-300 px-6 py-2">
                        {{ __('Sign In') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>