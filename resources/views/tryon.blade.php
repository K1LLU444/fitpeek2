<style>
    /* Hide Scrollbar */
    .scroll-container {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
        overflow-x: scroll;
        position: relative;
    }
    .scroll-container::-webkit-scrollbar {
        display: none; /* Chrome, Safari, and Opera */
    }
</style>

<x-app-layout>
    <body class="bg-gray-100 px-4 sm:px-6 lg:px-8">
        <!-- Product Details Section -->
        <div class="flex flex-col md:flex-row bg-white p-4 sm:p-6 rounded-lg max-w-4xl mx-auto mt-20 gap-4 sm:gap-6 shadow-md">
            <div class="w-full md:w-1/2 flex justify-center items-center bg-gray-200 rounded-lg p-4 sm:p-6">
                <div class="bg-gray-300 w-[180px] sm:w-[220px] md:w-[280px] lg:w-[350px] h-[220px] sm:h-[260px] md:h-[320px] lg:h-[400px] flex items-center justify-center rounded-md">
                        <p>No avatar found</p>
                </div>
            </div>

            <div class="w-full md:w-1/2 bg-white p-4 sm:p-6 rounded-lg shadow-sm">
                <h2 class="text-lg sm:text-xl font-bold text-center md:text-left">Product Name</h2>
                <span class="inline-block bg-green-200 text-green-800 text-xs font-semibold px-3 py-1 rounded-md mt-2">
                    Tag
                </span>
                <div class="flex justify-center md:justify-start items-center mt-2">
                    <span class="text-lg font-semibold">Php</span>
                    <span class="text-3xl font-bold ml-1">50</span>
                </div>
                <p class="text-gray-700 text-sm sm:text-base mt-4 leading-relaxed text-center md:text-left">
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore..."
                </p>
                <p class="text-sm font-semibold mt-4 text-center md:text-left">Recommended Size: Medium</p>
                <label for="size" class="block text-sm font-medium mt-4 text-center md:text-left">Sizes</label>
                <select id="size" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">
                    <option>--Pick your Size--</option>
                    <option>Small</option>
                    <option>Medium</option>
                    <option>Large</option>
                </select>
                <button class="w-full bg-accent-color text-white font-semibold py-2 mt-4 rounded-lg hover:bg-accent-hover transition-all duration-300">
                    Try-on
                </button>
            </div>
        </div>

        <!-- Horizontal Scroll Section -->
        <div class="bg-gray-100 p-4 sm:p-6 mt-6 sm:mt-8">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-lg sm:text-xl font-semibold mb-3 text-left">Recommended</h2>

                <!-- Scrollable container with Next/Prev buttons -->
                <div class="relative">
                    <div id="scrollContainerPrev" class="scroll-container whitespace-nowrap p-2 flex scroll-smooth">
                        <div class="flex space-x-4 pb-4">
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 1" price="5" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 2" price="10" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 3" price="20" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 4" price="30" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 5" price="40" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 1" price="5" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 2" price="10" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 3" price="20" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 4" price="30" />
                            <x-horizontalscrollbar class="w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 5" price="40" />
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
        <x-footer />
    </body>
</x-app-layout>
