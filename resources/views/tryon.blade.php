<x-app-layout>
    <body class="bg-gray-100 px-4 sm:px-6 lg:px-8">


        <!-- Product Details Section -->
        <div class="flex flex-col md:flex-row bg-white p-4 sm:p-6 rounded-lg max-w-4xl mx-auto mt-20 gap-4 sm:gap-6 shadow-md">
            <!-- Image -->
            <div class="w-full md:w-1/2 flex justify-center items-center bg-gray-200 rounded-lg p-4 sm:p-6">
                <div class="bg-gray-300 w-[180px] sm:w-[220px] md:w-[280px] lg:w-[350px] h-[220px] sm:h-[260px] md:h-[320px] lg:h-[400px] flex items-center justify-center rounded-md">
                    @if($product && $product->front_image)
                        <img src="{{ $product->front_image }}" class="w-full h-full object-contain" alt="Scraped Product">
                    @else
                        <span class="text-gray-500 text-sm">No image found</span>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="w-full md:w-1/2 bg-white p-4 sm:p-6 rounded-lg shadow-sm">
                <h2 class="text-lg sm:text-xl font-bold text-center md:text-left">
                    {{ $product->product_name ?? 'Product Name' }}
                </h2>

                <div class="flex justify-center md:justify-start items-center mt-2">
                    <span class="text-lg font-semibold">Php</span>
                    <span class="text-3xl font-bold ml-1">{{ $product->price ?? '0.00' }}</span>
                </div>

                <p class="text-gray-700 text-sm sm:text-base mt-4 leading-relaxed text-center md:text-left">
                    {{ $product->description ?? 'No description available.' }}
                </p>

                @if($product->size_chart_image)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Size Chart:</p>
                        <img src="{{ $product->size_chart_image }}" alt="Size Chart" class="max-w-xs rounded-md shadow mx-auto">
                    </div>
                @endif

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

        <!-- Other Sections -->
        <div class="bg-gray-100 min-h-screen p-4 sm:p-6 mt-6 sm:mt-8">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-lg sm:text-xl font-semibold mb-3 text-left">Previous picks</h2>
                <div class="overflow-x-auto whitespace-nowrap p-2 flex justify-left">
                    <div class="flex space-x-4 pb-4">
                        <x-horizontalscrollbar image="image.jpg" text="Product 1" price="5" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 2" price="10" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 3" price="20" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 4" price="30" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 5" price="40" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 6" price="50" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 7" price="60" />
                    </div>
                </div>

                <h2 class="text-lg sm:text-xl font-semibold mb-3 mt-6 sm:mt-8 text-left">For you</h2>
                <div class="overflow-x-auto whitespace-nowrap p-2 flex justify-center">
                    <div class="flex space-x-4 pb-4">
                        <x-horizontalscrollbar image="image.jpg" text="Product 1" price="10" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 2" price="20" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 3" price="30" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 4" price="40" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 5" price="50" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 6" price="60" />
                        <x-horizontalscrollbar image="image.jpg" text="Product 7" price="70" />
                    </div>
                </div>
            </div>
        </div>

        <x-footer />
    </body>
</x-app-layout>
