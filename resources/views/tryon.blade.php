<x-app-layout>
    <body class="bg-gray-100 px-4 sm:px-6 lg:px-8">
        <!-- Product Details Section -->
        <div class="flex flex-col md:flex-row bg-white p-4 sm:p-6 rounded-lg max-w-4xl mx-auto mt-20 :mt-10 gap-4 sm:gap-6 shadow-md">

            <div class="w-full md:w-1/2 flex justify-center items-center bg-gray-200 rounded-lg p-4 sm:p-6">
                <div class="bg-gray-300 w-[180px] sm:w-[220px] md:w-[280px] lg:w-[350px] h-[220px] sm:h-[260px] md:h-[320px] lg:h-[400px] flex items-center justify-center rounded-md">
                    <img class="w-full h-full object-contain" alt="Avatar">
                </div>
            </div>

            <div class="w-full md:w-1/2 bg-white p-4 sm:p-6 rounded-lg shadow-sm">
                <h2 class="text-lg sm:text-xl font-bold text-center md:text-left">&lt;Product Name&gt;</h2>

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

                <button class="w-full bg-[#a68a75] text-white font-semibold py-2 mt-4 rounded-lg hover:bg-[#8c7663] transition-all duration-300">
                    Try-on
                </button>
            </div>
        </div>

        <div class="bg-gray-100 min-h-screen p-4 sm:p-6 mt-6 sm:mt-8">
            <div class="max-w-7xl mx-auto">
                <h2 class="text-lg sm:text-xl font-semibold mb-3 text-left">Previous picks</h2>
                <div class="overflow-x-auto whitespace-nowrap p-2 flex justify-left">
                    <div class="flex space-x-4 pb-4">
                        <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px] mx-auto flex justify-center" image="image.jpg" text="Product 1" price="5" />
                        <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px] mx-auto flex justify-center" image="image.jpg" text="Product 2" price="10" />
                        <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px] mx-auto flex justify-center" image="image.jpg" text="Product 3" price="20" />
                        <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px] mx-auto flex justify-center" image="image.jpg" text="Product 4" price="30" />
                        <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px] mx-auto flex justify-center" image="image.jpg" text="Product 5" price="40" />
                        <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px] mx-auto flex justify-center" image="image.jpg" text="Product 6" price="50" />
                        <x-horizontalscrollbar class="w-full sm:w-[180px] md:w-[200px] lg:w-[250px] mx-auto flex justify-center" image="image.jpg" text="Product 7" price="60" />
                    </div>
                </div>
                <h2 class="text-lg sm:text-xl font-semibold mb-3 mt-6 sm:mt-8 text-left">For you</h2>

                <div class="overflow-x-auto whitespace-nowrap p-2 flex justify-center">
                    <div class="flex space-x-4 pb-4">
                        <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 1" price="10" />
                        <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 2" price="20" />
                        <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 3" price="30" />
                        <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 4" price="40" />
                        <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 5" price="50" />
                        <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 6" price="60" />
                        <x-horizontalscrollbar class="w-[140px] sm:w-[160px] md:w-[180px] mx-auto" image="image.jpg" text="Product 7" price="70" />
                    </div>
                </div>
            </div>
        </div>

        <x-footer />
    </body>
</x-app-layout>
