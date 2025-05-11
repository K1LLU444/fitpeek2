@php
    $pose = $product->pose === 'low' ? 'low' : 'high';
    $avatarFrontPath = $pose === 'low' ? $avatar->avatar_cleaned_front_lower_path : $avatar->avatar_cleaned_front_path;
    $avatarBackPath = $pose === 'low' ? $avatar->avatar_cleaned_back_lower_path : $avatar->avatar_cleaned_back_path;
    $sizeChartJson = json_encode($product->size_chart ?? []);
    $initialSize = array_key_first($product->size_chart ?? []) ?? '';
@endphp

<script>
    const sizeData = {!! $sizeChartJson !!};
    let selectedSize = "{{ $initialSize }}";
</script>

<div class="w-full sm:max-w-[1100px] mx-auto p-6">
    <div class="grid grid-cols-1 lg:grid-cols-[45%_55%] gap-6">

        <!-- LEFT: Avatar Container -->
        <div class="bg-white rounded-2xl shadow-md p-6 flex flex-col items-center justify-start gap-4 h-[700px] overflow-hidden">

            <!-- Top Controls -->
            <div class="w-full flex flex-wrap items-center justify-center gap-4">
                <div class="flex items-center gap-1">
                    <label for="product-size-select" class="text-xs font-medium text-gray-600">Size:</label>
                    <select id="product-size-select" class="rounded-md border border-gray-300 text-xs w-[60px] px-2 py-1 shadow-sm text-left">
                        <option value="">--</option>
                        @foreach(array_keys($product->size_chart ?? []) as $size)
                            <option value="{{ $size }}">{{ $size }}</option>
                        @endforeach
                    </select>
                </div>

                <button id="toggle-avatar-view" class="bg-white hover:bg-gray-200 border border-gray-300 text-xs font-semibold px-2 py-1 rounded">Front</button>

                <button id="toggle-overlay-visibility" class="invisible bg-white hover:bg-gray-200 border border-gray-300 text-xs font-semibold px-2 py-1 rounded">
                    <img id="visibility-icon" src="/images/weather/top-bottom.png" alt="Visibility Icon" class="w-4 h-4">
                </button>
            </div>

            <!-- Avatar Display -->
            <div id="avatar-container"
                data-avatar-height="{{ $avatar->height }}"
                data-height-unit="{{ $avatar->unit }}"
                data-avatar-waist="{{ $avatar->waist }}"
                class="relative w-full h-[600px] p-6 bg-gray-100 rounded-xl flex items-center justify-center shadow overflow-visible">

                <img id="avatar-image"
                    data-front="{{ asset($avatarFrontPath) }}"
                    data-back="{{ asset($avatarBackPath) }}"
                    src="{{ asset($avatarFrontPath) }}"
                    alt="Avatar"
                    class="max-h-full object-contain mx-auto pointer-events-none transition-all duration-300">

                <img id="overlay-image"
                    data-front="{{ asset($product->front_image) }}"
                    data-back="{{ asset($product->back_image) }}"
                    class="absolute left-0 right-0 mx-auto object-contain pointer-events-none transition-all duration-300 ease-in-out"
                    style="display: none;"
                    alt="Overlay">
            </div>
        </div>

        <!-- RIGHT: Product Info -->
        <div class="bg-white rounded-2xl shadow-md p-6 w-full overflow-y-auto max-h-[700px]">
            <h2 class="text-xl font-bold mb-2">{{ $product->product_name }}</h2>
            <p class="text-gray-700 font-medium mb-2">₱{{ number_format($product->price, 2) }}</p>
            <p class="text-gray-600 mb-4">{{ $product->description }}</p>

            <p class="text-sm text-gray-500">Style: {{ implode(', ', $product->tag_styles ?? []) }}</p>
            <p class="text-sm text-gray-500">Event: {{ implode(', ', $product->tag_events ?? []) }}</p>
            <p class="text-sm text-gray-500">Weather: {{ implode(', ', $product->tag_weather ?? []) }}</p>

            @if($product->size_chart)
                <h3 class="text-md font-semibold mt-4 mb-2">Size Chart</h3>
                <table class="text-sm border w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border px-2 py-1">Size</th>
                            <th class="border px-2 py-1">Width</th>
                            <th class="border px-2 py-1">Length</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($product->size_chart as $size => $entry)
                        <tr>
                            <td class="border px-2 py-1">{{ $size }}</td>
                            <td class="border px-2 py-1">{{ $entry['width'] ?? '-' }}</td>
                            <td class="border px-2 py-1">{{ $entry['length'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if($product->product_link)
                <a href="{{ $product->product_link }}" target="_blank" class="inline-block mt-4 text-blue-600 underline">
                    View Product on Store ↗
                </a>
            @endif
        </div>
    </div>
</div>

{{-- JS --}}
<script>
let showingFront = true;
let currentUnit = "cm";
let overlayVisible = true;
const overlayType = "{{ $product->product_type }}"; // top or bottom

document.addEventListener("DOMContentLoaded", function () {
    const avatarImage = document.getElementById("avatar-image");
    const overlayImage = document.getElementById("overlay-image");
    const avatarContainer = document.getElementById("avatar-container");

    const toggleViewBtn = document.getElementById("toggle-avatar-view");
    const sizeSelect = document.getElementById("product-size-select");
    const visibilityBtn = document.getElementById("toggle-overlay-visibility");
    const visibilityIcon = document.getElementById("visibility-icon");

    const frontSrc = avatarImage.dataset.front;
    const backSrc = avatarImage.dataset.back;

    avatarImage.src = frontSrc;

    toggleViewBtn.addEventListener('click', () => {
        showingFront = !showingFront;
        avatarImage.src = showingFront ? frontSrc : backSrc;
        toggleViewBtn.textContent = showingFront ? "Front" : "Back";
        updateOverlay();
    });

    sizeSelect.value = selectedSize;
    sizeSelect.addEventListener('change', (e) => {
        selectedSize = e.target.value;
        updateOverlay();
    });

    visibilityBtn.addEventListener("click", () => {
        overlayVisible = !overlayVisible;
        overlayImage.style.display = overlayVisible ? "block" : "none";
        visibilityIcon.src = overlayVisible
            ? "/images/weather/top-bottom.png"
            : "/images/weather/hidden.png";
    });

    avatarImage.onload = () => {
        updateOverlay();
    };

    function updateOverlay() {
        const selected = sizeData[selectedSize] || {};
        const avatarHeightCm = normalizeUnit(parseFloat(avatarContainer.dataset.avatarHeight || "165"), avatarContainer.dataset.heightUnit);
        const avatarPixelHeight = avatarImage.clientHeight || 480;

        const topOffset = avatarImage.getBoundingClientRect().top - avatarContainer.getBoundingClientRect().top;
        const neckOffsetPx = topOffset + 48;
        const waistOffsetPx = topOffset + 132;

        const clothingLengthCm = selected.length || 30;
        const heightPx = (clothingLengthCm / avatarHeightCm) * avatarPixelHeight;

        // ✅ Use data attributes instead of hardcoding asset()
        const clothingSrc = showingFront
            ? overlayImage.dataset.front
            : overlayImage.dataset.back;

        overlayImage.src = clothingSrc;
        overlayImage.style.display = overlayVisible ? "block" : "none";
        overlayImage.style.position = "absolute";
        overlayImage.style.height = heightPx + "px";
        overlayImage.style.top = overlayType === "bottom" ? waistOffsetPx + "px" : neckOffsetPx + "px";
        overlayImage.style.left = "0";
        overlayImage.style.right = "0";
        overlayImage.style.margin = "0 auto";
        overlayImage.style.clipPath = "none";
    }


    function normalizeUnit(value, unit) {
        unit = unit?.toLowerCase();
        return unit === "inches" || unit === "in" ? value * 2.54 : value;
    }
});
</script>
