<nav x-data="{ open: false, profileOpen: false }" class="bg-white border-b border-gray-100 fixed top-0 z-50 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Logo & Brand -->
            <div class="flex items-center space-x-3">
                <a href="/" class="flex items-center">
                    <img src="{{ asset('images/logo_fitpeek.svg') }}" alt="FitPeek Logo" class="h-10 w-auto">
                    <span class="text-2xl font-semibold ml-2">FitPeek</span>
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="hidden sm:flex space-x-6 items-center">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Home') }}
                <x-nav-link :href="route('avatar.create')" :active="request()->routeIs('avatar.create')">
                    {{ __('Your Avatar') }}
                </x-nav-link>
                <x-nav-link :href="route('aboutus')" :active="request()->routeIs('aboutus')">
                    {{ __('About Us') }}
                </x-nav-link>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button @click="profileOpen = !profileOpen" class="flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-100 hover:bg-gray-200 focus:outline-none">
                    <span class="truncate max-w-[120px] overflow-hidden text-ellipsis whitespace-nowrap block">
                        {{ Auth::user()->name }}
                    </span>
                    <svg 
                        class="ml-1 h-4 w-4 transition-transform text-gray-600" 
                        :class="{ 'rotate-180': profileOpen }" 
                        xmlns="http://www.w3.org/2000/svg" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor" 
                        stroke-width="2">
                        <path 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            d="M19 9l-7 7-7-7" />
                    </svg>
                    </button>

                    <div x-show="profileOpen" @click.away="profileOpen = false" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-md z-50">
                    <div class="px-4 py-3 text-gray-700 w-[200px] max-w-[250px]">
                        <p class="text-sm font-semibold break-words">{{ Str::limit(Auth::user()->name, 40) }}</p>
                        <p class="text-xs break-all text-gray-500 truncate" title="{{ Auth::user()->email }}">
                            {{ Auth::user()->email }}</p>
                    </div>
                        <div class="border-t border-gray-100">
                            <x-responsive-nav-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-responsive-nav-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Hamburger -->
            <div class="sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
        </div>
    </div>
</nav>
