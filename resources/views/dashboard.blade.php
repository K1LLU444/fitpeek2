<x-app-layout>
    <div class="w-full">
        <div class="mt-[60px]">
            <img src="{{ asset('images/banner-home.jpg') }}" 
                 alt="Home Page Background" 
                 class="w-full mb-10">
        </div> 
        
        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 bg-gray-100">
            <div x-data="filterToggle" class="lg:col-span-1 p-4 flex flex-col items-center">
                <!-- Search Bar + Toggle (Mobile Only Layout) -->
                <div class="w-full max-w-96 flex items-center justify-between lg:block">
                    <!-- Toggle Filter Button - Visible only on mobile -->
                    <button @click="toggleFilters"
                        class="lg:hidden bg-white text-gray-500 shadow-md px-3 py-2 rounded-md hover:bg-gray-200 transition mr-2">
                        Filters
                    </button>

                    <!-- Search Bar (Always Visible) -->
                    <div class="relative flex-1">
                        <img src="{{ asset('images/searchbar.svg') }}" 
                            alt="Search" 
                            class="absolute left-3 w-5 h-5 top-1/2 transform -translate-y-1/2 text-gray-600">
                        <input type="text" 
                            placeholder="Search..." 
                            class="rounded-3xl p-2 shadow-sm w-full pl-10 pr-3 text-gray-700 border border-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none">
                    </div>
                </div>

                <!-- Filter Section -->
                <div 
                    x-show="showFilters"
                    x-cloak
                    x-transition
                    class="w-full max-w-96 mt-4 p-4 bg-white shadow-md rounded-lg overflow-y-auto">
                    
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
            <div class="lg:col-span-2 p-4">
                <!-- Search Header -->
                <div class="max-w-6xl mx-auto text-left mb-6">
                    <h1 class="text-2xl font-semibold mb-4 text-center lg:text-left">What’s your style?</h1>

                    <!-- Search Bar + Button (Responsive) -->
                    <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-2">
                        <div class="relative w-full max-w-lg">
                            <img src="{{ asset('images/searchbar.svg') }}" 
                                alt="Search" 
                                class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600">
                            <input type="text" 
                                placeholder="Paste here the link" 
                                class="rounded-3xl p-2 shadow-sm w-full pl-10 text-gray-700 border border-gray-300">
                        </div>
                        <button class="bg-accent-color text-white px-4 py-2 rounded-lg w-full sm:w-auto">Submit</button>
                    </div>
                </div>

                <div class="max-w-6xl mx-auto space-y-8">
                    <h2 class="text-lg font-semibold mb-3 text-center lg:text-left">Previous Search</h2>

                    @php
                        $images = [
                            asset('images/Product1.jpg'),
                            asset('images/product2.jpg'),
                            asset('images/product3.jpg'),
                            asset('images/product4.jpg'),
                            asset('images/product5.jpg'),
                            asset('images/product6.jpg')
                        ];
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 justify-center">
                        @for ($i = 0; $i < count($images); $i++)
                            <div class="flex justify-center"> 
                                <x-card image="{{ $images[$i] }}" text="Product {{ $i + 1 }}" price="{{ rand(100, 999) }}" />
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <x-footer />

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('filterToggle', () => ({
            showFilters: window.innerWidth >= 1024,
            toggleFilters() {
                // Only toggle on mobile
                if (window.innerWidth < 1024) {
                    this.showFilters = !this.showFilters;
                }
            },
            handleResize() {
                this.showFilters = window.innerWidth >= 1024;
            },
            init() {
                window.addEventListener('resize', () => this.handleResize());
            }
        }));
    });
</script>
</x-app-layout>
