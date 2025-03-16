<style>
    /* Hide Scrollbar */
    .scroll-container {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
        overflow-x: scroll;
    }
    .scroll-container::-webkit-scrollbar {
        display: none; /* Chrome, Safari, and Opera */
    }
</style>

<x-app-layout>
    <div class="w-full">
        <div class="mt-1">
            <img src="{{ asset('images/banner-home.jpg') }}" 
                 alt="Home Page Background." 
                 class="w-full mb-10">
        </div> 
        
        <div class="block md:hidden px-4 mb-4">
            <button @click="openFilters = !openFilters" 
                class="w-full bg-gray-200 py-2 rounded-lg text-gray-700 font-semibold text-center">
                Filter
            </button>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 bg-gray-100">
            <div x-data="{ openFilters: true }" 
                :class="{'block': openFilters, 'hidden': !openFilters}" 
                class="lg:col-span-1 p-4 block md:block rounded-lg md:max-w-96 mx-auto overflow-hidden transition-all duration-300"
                x-ref="filters" x-show="openFilters"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2">
                
                <div class="relative w-full">
                    <img src="{{ asset('images/searchbar.svg') }}" 
                        alt="Search" 
                        class="absolute left-3 w-5 h-5 top-1/2 transform -translate-y-1/2 text-gray-600">
                    
                    <input type="text" 
                        placeholder="Search..." 
                        class="rounded-3xl p-2 shadow-sm w-full pl-10 pr-3 text-gray-700 border border-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none">
                </div>

                <!-- Filter Section -->
                <div class="w-full max-w-96 mx-auto mt-6 p-4 bg-white shadow-md rounded-lg
                md:h-[500px] lg:h-[550px] overflow-y-auto"">
                    <h2 class="text-lg font-semibold mb-2">FILTER</h2>

                    <!-- Selected Filters -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="flex items-center bg-gray-200 px-3 py-1 text-sm rounded-full">Spring ✕</span>
                        <span class="flex items-center bg-gray-200 px-3 py-1 text-sm rounded-full">Smart ✕</span>
                        <span class="flex items-center bg-gray-200 px-3 py-1 text-sm rounded-full">Modern ✕</span>
                    </div>

                    <!-- Checkbox Filters -->
                    <div class="space-y-3">
                        @foreach (['Label 1', 'Label 2', 'Label 3'] as $label)
                            <div class="flex items-start space-x-2">
                                <input type="checkbox" class="mt-1 rounded">
                                <div>
                                    <p class="text-sm font-medium">{{ $label }}</p>
                                    <p class="text-xs text-gray-500">Description</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Price Range -->
                    <div class="mt-4">
                        <label class="text-sm font-medium">Price Range</label>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>₱0</span>
                            <span>₱1000+</span>
                        </div>
                        <input type="range" class="w-full mt-1">
                    </div>

                    <!-- Color Filters -->
                    <div class="mt-4">
                        <p class="text-sm font-medium">Color</p>
                        <div class="space-y-2 mt-1">
                            @foreach (['Red', 'Blue', 'Green'] as $color)
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" class="rounded">
                                    <span class="text-sm">{{ $color }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Next Button (Right) -->
                    <button id="nextBtnPrev" class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-gray-200 hover:bg-gray-300 p-2 rounded-full shadow-lg">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Previous Button (Left) -->
                    <button id="prevBtnPrev" class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-gray-200 hover:bg-gray-300 p-2 rounded-full shadow-lg">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                </div>

                <h2 class="text-lg sm:text-xl font-semibold mb-3 mt-6 sm:mt-8 text-left">For you</h2>

                <!-- Scrollable container with Next/Prev buttons -->
                <div class="relative">
                    <div id="scrollContainerForYou" class="scroll-container whitespace-nowrap p-2 flex scroll-smooth">
                        <div class="flex space-x-4 pb-4">
                            <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 1" price="10" />
                            <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 2" price="20" />
                            <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 3" price="30" />
                            <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 4" price="40" />
                            <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 5" price="50" />
                            <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 6" price="10" />
                            <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 7" price="20" />
                        </div>
                    </div>

                    <!-- Next Button (Right) -->
                    <button id="nextBtnForYou" class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-gray-200 hover:bg-gray-300 p-2 rounded-full shadow-lg">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Previous Button (Left) -->
                    <button id="prevBtnForYou" class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-gray-200 hover:bg-gray-300 p-2 rounded-full shadow-lg">
                        <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <x-footer />
    </body>
</x-app-layout>

<!-- JavaScript for Scrolling Functionality -->
<script>
    // Scroll Next/Previous for Previous Picks
    document.getElementById('nextBtnPrev').addEventListener('click', function () {
        document.getElementById('scrollContainerPrev').scrollBy({ left: 300, behavior: 'smooth' });
    });

    document.getElementById('prevBtnPrev').addEventListener('click', function () {
        document.getElementById('scrollContainerPrev').scrollBy({ left: -300, behavior: 'smooth' });
    });

    // Scroll Next/Previous for "For You" section
    document.getElementById('nextBtnForYou').addEventListener('click', function () {
        document.getElementById('scrollContainerForYou').scrollBy({ left: 300, behavior: 'smooth' });
    });

    document.getElementById('prevBtnForYou').addEventListener('click', function () {
        document.getElementById('scrollContainerForYou').scrollBy({ left: -300, behavior: 'smooth' });
    });
</script>
