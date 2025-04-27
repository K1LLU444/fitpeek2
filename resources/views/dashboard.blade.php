<x-app-layout>
    <div class="w-full">
        <div class="mt-1">
            <img src="{{ asset('images/banner-home.jpg') }}" 
                 alt="Home Page Background" 
                 class="w-full mb-10">
        </div> 
        
        <div class="block md:hidden px-4 mb-4">
            <button @click="openFilters = !openFilters" 
                class="w-full bg-gray-200 py-2 rounded-lg text-gray-700 font-semibold text-center">
                Filter
            </button>
        </div>


        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-4 bg-gray-100">
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
                md:h-[500px] lg:h-[550px] overflow-y-auto">
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
                            <span>₱100</span>
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

                    <!-- Size Filters -->
                    <div class="mt-4">
                        <p class="text-sm font-medium">Size</p>
                        <div class="space-y-2 mt-1">
                            @foreach (['Small', 'Medium', 'Large'] as $size)
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" class="rounded">
                                    <span class="text-sm">{{ $size }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="lg:col-span-3 p-4">
                <!-- Search Header -->
                <div class="max-w-6xl mx-auto text-left mb-6">
                    <h1 class="text-2xl font-semibold mb-4 text-center lg:text-left">What’s your style?</h1>

                    <!-- Search Bar + Button (Responsive) -->
                    <form method="POST" action="{{ route('scrape.product') }}" class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-2" id="scrapeForm">
                        @csrf
                        <div class="relative w-full max-w-lg">
                            <img src="{{ asset('images/searchbar.svg') }}" 
                                alt="Search" 
                                class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600">
                            <input type="text" 
                                name="product_url"
                                placeholder="Paste here the link" 
                                required
                                class="rounded-3xl p-2 shadow-sm w-full pl-10 text-gray-700 border border-gray-300">
                        </div>
                        {{-- Output or error message --}}
                        @if(session('message'))
                            <div class="mt-4 text-green-600">{{ session('message') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="mt-4 text-red-600">{{ session('error') }}</div>
                        @endif
                        <button type="submit" class="bg-black text-white px-4 py-2 rounded-lg w-full sm:w-auto">Submit</button>
                    </form>

                    <div id="loader" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
                        <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-white ml-4">Fetching product...</span>
                    </div>

                    <script>
                        const form = document.getElementById('scrapeForm');

                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            document.getElementById('loader').classList.remove('hidden');

                            // Small delay to allow spinner to appear before actual submission
                            setTimeout(() => {
                                form.submit();
                            }, 100); // 100ms is enough
                        });
                    </script>
                </div>

                <div class="max-w-6xl mx-auto space-y-8">
                    <h2 class="text-lg font-semibold mb-3 text-center lg:text-left">Previous Search</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 justify-center">
                        @forelse ($products as $product)
                            <div class="flex justify-center">
                            @php
                                $fallbackImage = $product->back_image ?? null;
                                $displayImage = $product->front_image ?: $fallbackImage;
                                $imageSrc = Str::startsWith($displayImage, ['http://', 'https://'])
                                    ? $displayImage
                                    : asset($displayImage);
                            @endphp

                            <x-card 
                                :link="route('product.show', $product->id)"
                                image="{{ $displayImage ? $imageSrc : asset('images/placeholder.png') }}"
                                text="{{ $product->product_name }}"
                                price="{{ $product->price }}"
                            />

                            </div>
                        @empty
                            <p class="text-center text-gray-500 col-span-full">No products found.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div> 
    <x-footer />

    @if(session('show_modal') && is_array(session('product_images')))
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="imageModal">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-5xl p-6 relative max-h-[90vh] overflow-y-auto">
            <h2 class="text-2xl font-bold mb-2 text-center">Choose Front & Back Images</h2>
            <p class="text-sm text-center text-red-600 mb-6">
                Please choose images with <strong>white background</strong> and <strong>no person wearing the shirt</strong>.
            </p>

            <form method="POST" action="{{ route('products.select-images') }}" id="taggingForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ session('product_id') }}">
                <input type="hidden" name="front_image" id="frontImageInput">
                <input type="hidden" name="back_image" id="backImageInput">

                {{-- FRONT --}}
                <h3 class="text-lg font-semibold mb-2 text-center">Front</h3>
                <div class="flex justify-center gap-4 mb-2 flex-wrap">
                    @foreach(session('product_images') as $index => $img)
                    <div class="cursor-pointer border-4 border-transparent hover:border-blue-400 rounded"
                        onclick="selectImage('front', '{{ $img }}', this)">
                        <img src="{{ $img }}" class="w-32 h-32 object-cover rounded">
                    </div>
                    @endforeach
                </div>
                <p class="text-center text-sm text-gray-500 mb-6">
                    (<span id="frontSelected">None</span>)
                </p>

                {{-- BACK --}}
                <h3 class="text-lg font-semibold mb-2 text-center">Back</h3>
                <div class="flex justify-center gap-4 mb-2 flex-wrap">
                    @foreach(session('product_images') as $index => $img)
                    <div class="cursor-pointer border-4 border-transparent hover:border-blue-400 rounded"
                        onclick="selectImage('back', '{{ $img }}', this)">
                        <img src="{{ $img }}" class="w-32 h-32 object-cover rounded">
                    </div>
                    @endforeach
                </div>
                <p class="text-center text-sm text-gray-500 mb-6">
                    (<span id="backSelected">None</span>)
                </p>

                {{-- SUBMIT --}}
                <div class="text-center mt-4">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Done Tagging
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS --}}
    <script>
        function selectImage(type, url, el) {
            const inputId = type + 'ImageInput';
            const selectedText = document.getElementById(type + 'Selected');
            const currentlySelected = document.querySelector(`[data-selected-${type}]`);

            // If user clicks the same selected image → unselect it
            if (currentlySelected === el) {
                el.classList.remove('border-green-500');
                el.classList.add('border-transparent');
                el.removeAttribute(`data-selected-${type}`);
                document.getElementById(inputId).value = '';
                selectedText.innerText = 'None';
                return;
            }

            // Clear old selection
            if (currentlySelected) {
                currentlySelected.classList.remove('border-green-500');
                currentlySelected.classList.add('border-transparent');
                currentlySelected.removeAttribute(`data-selected-${type}`);
            }

            // Highlight new selection
            el.classList.remove('border-transparent');
            el.classList.add('border-green-500');
            el.setAttribute(`data-selected-${type}`, true);

            // Update hidden input + text
            document.getElementById(inputId).value = url;
            const index = Array.from(el.parentNode.children).indexOf(el) + 1;
            selectedText.innerText = 'Image ' + index;
        }
    </script>

    @endif


</x-app-layout>
