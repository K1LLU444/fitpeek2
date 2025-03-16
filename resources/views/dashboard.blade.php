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
    <body class="bg-gray-100 px-4 sm:px-6 lg:px-8">
        <!-- Horizontal Scroll Section -->
        <div class="bg-gray-100 min-h-screen p-4 sm:p-6 mt-6 sm:mt-8">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-lg sm:text-xl font-semibold mb-3 text-left">Previous picks</h2>

                <!-- Scrollable container with Next/Prev buttons -->
                <div class="relative">
                    <div id="scrollContainerPrev" class="scroll-container whitespace-nowrap p-2 flex scroll-smooth">
                        <div class="flex space-x-4 pb-4">
                            <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 1" price="5" />
                            <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 2" price="10" />
                            <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 3" price="20" />
                            <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 4" price="30" />
                            <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 5" price="40" />
                            <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 3" price="20" />
                            <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 4" price="30" />
                            <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px]" image="image.jpg" text="Product 5" price="40" />
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
