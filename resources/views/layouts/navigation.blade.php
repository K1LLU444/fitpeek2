<nav x-data="{ open: false, profileOpen: false }" class="bg-white border-b border-gray-100 fixed top-0 z-50 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <a href="/" class="flex items-center text-2xl">
                <img src="{{ asset('images/logo_fitpeek.svg') }}" alt="FitPeek Logo" class="h-12 w-auto mr-2">
                FitPeek
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden sm:flex space-x-8">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Home') }}
                </x-nav-link>
                <x-nav-link :href="route('tryon')" :active="request()->routeIs('tryon')">
                    {{ __('Try-on') }}
                </x-nav-link>
                <x-nav-link :href="route('createavatar')" :active="request()->routeIs('createavatar')">
                    {{ __('Your Avatar') }}
                </x-nav-link>
                <x-nav-link :href="route('aboutus')" :active="request()->routeIs('aboutus')">
                    {{ __('About Us') }}
                </x-nav-link>
            </div>

            <!-- Profile Dropdown (Desktop & Mobile) -->
            <div class="relative sm:order-2 order-1 ml-[350px]">
                <button @click="profileOpen = !profileOpen" class="flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-100 hover:bg-gray-200 focus:outline-none">
                    <span>{{ Auth::user()->name }}</span>
                    <svg class="ml-1 h-4 w-4" :class="{'rotate-180': profileOpen, 'rotate-0': !profileOpen}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Profile Dropdown Menu (Visible in Mobile & Desktop) -->
                <div :class="{'block': profileOpen, 'hidden': !profileOpen}" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-md">
                    <div class="px-4 py-3 text-gray-700">
                        <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                        <p class="text-xs">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Button (Hamburger) - ONLY on Small Screens -->
            <div class="-me-2 flex items-center sm:hidden order-2">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu (Appears Under Toggle) -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden absolute right-0 mt-1 w-48 bg-white border border-gray-300 rounded-lg shadow-md">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tryon')" :active="request()->routeIs('tryon')">
                {{ __('Try-on') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('createavatar')" :active="request()->routeIs('createavatar')">
                {{ __('Your Avatar') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('aboutus')" :active="request()->routeIs('aboutus')">
                {{ __('About Us') }}
            </x-responsive-nav-link>
        </div>
    </div>
</nav>
