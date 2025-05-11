<x-app-layout>
    <div class="w-full">
        <div class="mt-16 md:mt-3">
            <img src="{{ asset('images/banner-home.jpg') }}" 
                 alt="Home Page Background" 
                 class="w-full">
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
            </div>            
        </div>
        <!-- Main Content -->
        <div class="lg:col-span-3 p-4">
            <!-- Search Header -->
            <div class="max-w-7xl mx-auto text-left">

        </div>

<div class="grid grid-cols-2 md:grid-cols-5 gap-4 max-w-xl md:max-w-7xl mx-auto mb-6">
    <!-- Row 1 -->
    <select id="filter-sex" class="w-full md:w-auto border rounded px-3 py-2 text-sm">
        <option value="">All Sex</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="unisex">Unisex</option>
    </select>

    <select id="filter-weather" class="w-full border rounded px-3 py-2 text-sm">
        <option value="">All Weather</option>
        <option value="Hot">Hot</option>
        <option value="Rainy">Rainy</option>
        <option value="Cool">Cool</option>
    </select>

    <!-- Row 2 -->
    <select id="filter-events" class="w-full border rounded px-3 py-2 text-sm">
        <option value="">All Events</option>
        <option value="Casual Outing">Casual Outing</option>
        <option value="Work">Work</option>
        <option value="School">School</option>
        <option value="Fitness">Fitness</option>
        <option value="Special Occasion">Special Occasion</option>
    </select>

    <select id="filter-style" class="w-full border rounded px-3 py-2 text-sm">
        <option value="">All Styles</option>
        <option value="Casual">Casual</option>
        <option value="Smart Casual">Smart Casual</option>
        <option value="Athleisure">Athleisure</option>
        <option value="Streetwear">Streetwear</option>
        <option value="Breezy">Breezy</option>
    </select>

    <!-- Row 3: Centered -->
    <div class="col-span-2 md:col-span-1 flex justify-center">
        <select id="filter-type" class="w-full md:w-full border rounded px-3 py-2 text-sm">
            <option value="">All Clothing</option>
            <option value="top">Tops Only</option>
            <option value="bottom">Bottoms Only</option>
        </select>
    </div>
</div>



            <div class="max-w-7xl mx-auto space-y-8">

                <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 justify-center">
                    @include('partials.product_cards')
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

    <script>
        function fetchFilteredProducts() {
            const sex = document.getElementById('filter-sex').value;
            const weather = document.getElementById('filter-weather').value;
            const events = document.getElementById('filter-events').value;
            const style = document.getElementById('filter-style').value;
            const type = document.getElementById('filter-type').value;

            const params = new URLSearchParams({ sex, weather, events, style, type });

            fetch(`{{ route('dashboard') }}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('product-grid').innerHTML = html;
            });
        }

        document.querySelectorAll('#filter-sex, #filter-weather, #filter-events, #filter-style, #filter-type')
            .forEach(select => select.addEventListener('change', fetchFilteredProducts));
    </script>


</x-app-layout>
