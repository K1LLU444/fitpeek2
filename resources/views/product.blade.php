<x-app-layout>
    <body class="bg-gray-100 px-4 sm:px-6 lg:px-8">

        <!-- Product Details Section -->
        <div class="flex flex-col md:flex-row bg-white p-4 sm:p-6 rounded-lg max-w-4xl mx-auto mt-20 gap-4 sm:gap-6 shadow-md">
            <!-- Image -->
            <div class="w-full md:w-1/2 flex justify-center items-center bg-gray-100 rounded-lg p-4 sm:p-6">
                <div class="w-[180px] sm:w-[220px] md:w-[280px] lg:w-[350px] h-[220px] sm:h-[260px] md:h-[320px] lg:h-[400px] flex items-center justify-center rounded-md">
                    @if($product && $product->front_image)
                        @php
                            $imageSrc = Str::startsWith($product->front_image, ['http://', 'https://'])
                                ? $product->front_image
                                : asset($product->front_image);
                        @endphp
                        <img src="{{ $imageSrc }}" class="w-full h-full object-contain" alt="{{ $imageSrc }}">
                    @else
                        <span class="text-gray-500 text-sm">No image found</span>
                    @endif
                </div>
            </div>


            <!-- Info -->
            <!-- PRODUCT NAME -->
            <div class="w-full md:w-1/2 bg-white p-4 sm:p-6 rounded-lg shadow-sm">
                <h2 class="text-lg sm:text-3xl font-bold text-center md:text-left">
                    {{ $product->product_name ?? 'Product Name' }}
                </h2>
            <!-- PRODUCT PRICE -->
                <div class="flex justify-center md:justify-start items-center mt-2">
                    <span class="text-lg font-semibold">Php</span>
                    <span class="text-2xl font-bold ml-1">{{ $product->price ?? '0.00' }}</span>
                </div>
            <!-- PRODUCT DESCRIPTION -->
                @if (!empty($product->description))
                    @php
                        $lines = explode("\n", $product->description);
                        $firstLine = array_shift($lines); // Remove first line and store it
                    @endphp

                    <p class="text-base sm:text-lg text-gray-800 mt-4 md:text-left">
                        {{ $firstLine }}
                    </p>

                    <ul class="text-gray-700 text-sm sm:text-base mt-2 leading-relaxed list-disc pl-5 space-y-1 text-left">
                        @foreach ($lines as $line)
                            @if (trim($line) !== '')
                                <li>{{ $line }}</li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 text-sm mt-4">No description available.</p>
                @endif
                <!-- SIZE CHART -->
                @if (!empty($product->size_chart_image))
                    <div class="mt-4">
                        <p class="text-base sm:text-lg text-gray-800 mt-4 md:text-left">Size Chart:</p>
                        <img src="{{ $product->size_chart_image }}" alt="Size Chart" class="max-w-xs rounded-md shadow mx-auto">
                    </div>
                @else
                    <p class="text-gray-500 text-sm mt-4 md:text-center">No size chart found.</p>
                @endif
                <!-- SIZES DROPDOWN -->
                @if($product->size_chart_data)
                    @php
                        $sizes = collect(json_decode($product->size_chart_data, true))->pluck('size')->unique();
                    @endphp

                    <div class="mt-4">
                        <label for="size" class="text-base sm:text-lg text-gray-800 mt-4 md:text-left">Sizes</label>
                        <select id="size" class="w-full mt-1 p-2 border border-gray-300 rounded-lg">
                            <option selected disabled>--Pick your Size--</option>
                            @foreach ($sizes as $size)
                                <option>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif


                <button class="w-full bg-accent-color text-white font-semibold py-2 mt-4 rounded-lg hover:bg-accent-hover transition-all duration-300">
                    Try-on
                </button>
            </div>
        </div>

        <!-- Other Sections -->
        @if ($otherProducts->count())
            <div class="bg-gray-100 min-h-screen p-4 sm:p-6 mt-6 sm:mt-8">
                <div class="max-w-7xl mx-auto">
                    <h2 class="text-lg sm:text-xl font-semibold mb-3 text-left">Previous picks</h2>
                    <div class="overflow-x-auto whitespace-nowrap p-2 flex justify-left">
                        <div class="flex space-x-4 pb-4">
                            @foreach ($otherProducts as $item)
                                <a href="{{ route('product.show', $item->id) }}">
                                    <x-horizontalscrollbar 
                                        image="{{ asset($item->front_image) }}"
                                        text="{{ $item->product_name }}"
                                        price="{{ $item->price }}"
                                    />
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <x-footer />
    </body>
</x-app-layout>
