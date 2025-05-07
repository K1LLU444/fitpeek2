@php use Illuminate\Support\Str; @endphp
<x-app-layout>
    <body class="bg-gray-100 px-4 sm:px-6 lg:px-8">

        <!-- Product Details Section -->
        <div class="flex flex-col md:flex-row bg-white p-4 sm:p-6 rounded-lg max-w-4xl mx-auto mt-20 gap-4 sm:gap-6 shadow-md">
            <!-- Image -->
            <div class="w-full md:w-1/2 flex justify-center items-center bg-gray-100 rounded-lg p-4 sm:p-6">
                <div class="flex flex-col gap-4 w-[180px] sm:w-[220px] md:w-[280px] lg:w-[350px] 
                            h-auto items-center justify-center rounded-md">

                    @php
                        $frontSrc = $product && $product->front_image
                            ? (Str::startsWith($product->front_image, ['http://', 'https://']) 
                                ? $product->front_image 
                                : asset($product->front_image))
                            : null;

                        $backSrc = $product && $product->back_image
                            ? (Str::startsWith($product->back_image, ['http://', 'https://']) 
                                ? $product->back_image 
                                : asset($product->back_image))
                            : null;
                    @endphp

                    @if($frontSrc || $backSrc)
                        @if($frontSrc)
                            <img src="{{ $frontSrc }}" class="w-full h-[300px] object-contain" alt="Front View">
                        @endif
                        @if($backSrc)
                            <img src="{{ $backSrc }}" class="w-full h-[300px] object-contain" alt="Back View">
                        @endif
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
                $descriptionLines = [];
                $skipping = false;

                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line)) continue;

                    // Start skipping on size chart indicators
                    if (
                        strtolower($line) === 'size:' ||
                        in_array(strtoupper($line), ['S', 'M', 'L', 'XL', 'XXL']) ||
                        stripos($line, 'waist') !== false ||
                        stripos($line, 'width') !== false ||
                        stripos($line, 'length') !== false ||
                        stripos($line, 'bottom') !== false ||
                        stripos($line, 'daily grind size chart') !== false ||
                        preg_match('/^\d+(\.\d+)?("|”|″)?$/', $line) // 30, 16", 11.5", etc.
                    ) {
                        $skipping = true;
                        continue;
                    }

                    // Resume when a normal line appears (likely after size block)
                    if ($skipping && preg_match('/[a-zA-Z]/', $line) && strlen($line) > 5) {
                        $skipping = false;
                    }

                    if (!$skipping) {
                        $descriptionLines[] = $line;
                    }
                }
            @endphp

            @if (!empty($descriptionLines))
                <ul class="text-gray-700 text-sm sm:text-base mt-4 leading-relaxed list-disc pl-5 space-y-1 text-left">
                    @foreach ($descriptionLines as $line)
                        <li>{{ $line }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-sm mt-4">No clean description available.</p>
            @endif
        @else
            <p class="text-gray-500 text-sm mt-4">No description available.</p>
        @endif




                <!-- SIZE CHART -->
                @if (!empty($product->size_chart_image))
                    <div class="mt-4">
                        <p class="text-base sm:text-lg text-gray-800 mt-4 md:text-left">Size Chart:</p>
                        <img src="{{ $product->size_chart_image }}" alt="Size Chart" class="max-w-xs rounded-md shadow mx-auto">
                    </div>
                @elseif (!empty($product->size_chart_data))
                    @php
                        $size_chart_data = is_string($product->size_chart_data) ? json_decode($product->size_chart_data, true) : $product->size_chart_data;
                    @endphp
                    @if (is_array($size_chart_data))
                        <div class="mt-4 overflow-x-auto">
                            <p class="text-base sm:text-lg text-gray-800 mt-4 md:text-left">Size Chart:</p>
                            <table class="min-w-full border text-sm text-left text-gray-700 mt-2">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border px-3 py-2">Size</th>
                                        @foreach ($size_chart_data as $sizeData)
                                            <th class="border px-3 py-2 text-center">{{ $sizeData['size'] }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $fields = array_filter(array_keys($size_chart_data[0]), fn($key) => $key !== 'size');
                                    @endphp
                                    @foreach ($fields as $field)
                                        <tr>
                                            <td class="border px-3 py-2 font-semibold capitalize">{{ str_replace('_', ' ', $field) }}</td>
                                            @foreach ($size_chart_data as $sizeData)
                                                <td class="border px-3 py-2 text-center">{{ $sizeData[$field] ?? '-' }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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



        <div class="max-w-5xl mx-auto mt-10 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- OUTFIT FORM (LEFT) -->
            <div class="bg-white p-6 rounded-xl shadow border">
            <h3 class="text-xl font-semibold  flex justify-center items-center gap-2 text-center w-full">
                Get Outfit Recommendation:
            </h3>
            <p class="text-m mb-6 flex justify-center items-center gap-2 text-center w-full">
                Please be specific with your inputs for optimal results.
            </p>


                <form method="POST" action="{{ route('chat.suggestOutfitForm') }}" class="space-y-4">
                    @csrf

                    <!-- Event -->
                    <div>
                        <label for="eventSelect" class="block text-sm font-medium mb-1">Event</label>
                        <select name="event" id="eventSelect" class="w-full p-2 border rounded" required onchange="toggleOtherEventInput()">
                            <option value="">Select an event...</option>
                            <option value="wedding">Wedding</option>
                            <option value="birthday">Birthday</option>
                            <option value="office">Office</option>
                            <option value="beach">Beach</option>
                            <option value="gala">Formal Gala</option>
                            <option value="other">Other (please specify)</option>
                        </select>
                        <input type="text" name="event_other" id="eventOtherInput"
                            placeholder="Describe the event..."
                            class="w-full mt-2 p-2 border rounded hidden" 
                            autocomplete="off"/>
                    </div>

                    <!-- Weather -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Weather</label>
                        <select name="weather" id="weatherSelect" class="w-full p-2 border rounded" required onchange="toggleOtherWeatherInput()">
                            <option value="">Select weather...</option>
                            <option value="sunny">Sunny</option>
                            <option value="rainy">Rainy</option>
                            <option value="cold">Cold</option>
                            <option value="hot">Hot</option>
                            <option value="windy">Windy</option>
                            <option value="other">Other (please specify)</option>
                        </select>
                        <input type="text" name="weather_other" id="weatherOtherInput"
                            placeholder="Describe the weather..."
                            class="w-full mt-2 p-2 border rounded hidden"
                            autocomplete="off" />
                    </div>


                    <!-- Style -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Style</label>
                        <select name="style" id="styleSelect" class="w-full p-2 border rounded" required onchange="toggleOtherStyleInput()">
                            <option value="">Select style...</option>
                            <option value="casual">Casual</option>
                            <option value="formal">Formal</option>
                            <option value="streetwear">Streetwear</option>
                            <option value="minimalist">Minimalist</option>
                            <option value="other">Other (please specify)</option>
                        </select>
                        <input type="text" name="style_other" id="styleOtherInput"
                            placeholder="Describe the style..."
                            class="w-full mt-2 p-2 border rounded hidden"
                            autocomplete="off" />
                    </div>


                    <!-- Extra Notes -->
                    <div>
                        <label class="block text-sm font-medium mb-1">Extra Notes (optional)</label>
                        <textarea name="notes" rows="3" class="w-full p-2 border rounded"
                                placeholder="e.g., Prefer oversized, love earth tones..."></textarea>
                    </div>

                    <button type="submit"
                            class="w-full bg-accent-color text-white font-semibold py-2 mt-4 rounded-lg hover:bg-accent-hover transition-all duration-300">
                        Suggest Outfit
                    </button>
                </form>
            </div>
<!-- RIGHT SIDE (RECOMMENDATION RESULT) -->
<div class="bg-white p-6 rounded-xl shadow border flex-1 overflow-y-auto max-h-[600px]">
    @if (session('suggestion'))
        <!-- 🧠 GPT Response -->
        <div class="w-full prose prose-sm sm:prose-base max-w-none text-gray-800">
            {!! Str::markdown(session('suggestion.message')) !!}
        </div>

        @php
            $ratings = session('suggestion.ratings') ?? [
                'average' => session('suggestion.average') ?? null,
                'total' => session('suggestion.total') ?? 0,
                'counts' => session('suggestion.counts') ?? [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0],
            ];
        @endphp

        <!-- ⭐ Rating Form (only if not yet rated) -->
        @if (!session('has_rated'))
            <div class="mt-6">
                <p class="text-sm text-gray-700 mb-1">How would you rate this suggestion?</p>

                <form method="POST" action="{{ route('chat.rateSuggestion') }}">
                    @csrf
                    <input type="hidden" name="hash" value="{{ session('suggestion.hash') }}">
                    <div class="flex items-center space-x-1 mt-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="submit" name="rating" value="{{ $i }}"
                                class="text-yellow-400 text-2xl hover:scale-110 transition">
                                ★
                            </button>
                        @endfor
                    </div>
                </form>
            </div>
        @endif

        <!-- 📊 Rating Stats -->
        @if ($ratings['average'])
            <div class="mt-6">
                <div class="flex items-center space-x-4">
                    <div class="text-4xl font-bold text-gray-800">{{ number_format($ratings['average'], 1) }}</div>
                    <div class="text-sm text-gray-500">{{ $ratings['total'] }} rating{{ $ratings['total'] == 1 ? '' : 's' }}</div>
                </div>

                @foreach ([5, 4, 3, 2, 1] as $star)
                    @php
                        $count = $ratings['counts'][$star] ?? 0;
                        $percentage = $ratings['total'] > 0 ? ($count / $ratings['total']) * 100 : 0;
                    @endphp
                    <div class="flex items-center space-x-2 mt-2">
                        <span class="w-4 text-sm text-gray-700">{{ $star }}★</span>
                        <div class="flex-1 bg-gray-200 rounded h-3">
                            <div class="bg-yellow-400 h-3 rounded" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-xs text-gray-500 w-6 text-right">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        @endif
    @else
        <!-- Placeholder if no suggestion yet -->
        <p class="text-gray-400 text-sm text-center mt-16">
            (The recommendation result will show up here)
        </p>
    @endif
</div>



        </div>


        <script>
            function toggleOtherEventInput() {
                const select = document.getElementById('eventSelect');
                const input = document.getElementById('eventOtherInput');
                input.classList.toggle('hidden', select.value !== 'other');
                input.required = (select.value === 'other');
            }

            function toggleOtherWeatherInput() {
                const select = document.getElementById('weatherSelect');
                const input = document.getElementById('weatherOtherInput');
                input.classList.toggle('hidden', select.value !== 'other');
                input.required = (select.value === 'other');
            }

            function toggleOtherStyleInput() {
                const select = document.getElementById('styleSelect');
                const input = document.getElementById('styleOtherInput');
                input.classList.toggle('hidden', select.value !== 'other');
                input.required = (select.value === 'other');
            }
        </script>





        <!-- Other Sections -->
        @if ($otherProducts->count())
            <div class="bg-gray-100 min-h-screen p-4 sm:p-6 mt-6 sm:mt-8">
                <div class="max-w-7xl mx-auto">
                    <h2 class="text-lg sm:text-xl font-semibold mb-3 text-left">Previous picks</h2>
                    <div class="overflow-x-auto">
                        <div class="flex space-x-4 pb-4">
                            @foreach ($otherProducts as $item)
                                <a href="{{ route('product.show', $item->id) }}">
                                @php
                                    $imagePath = $item->front_image ?: $item->back_image;
                                    $imageSrc = $imagePath
                                        ? (Str::startsWith($imagePath, ['http://', 'https://']) 
                                            ? $imagePath 
                                            : asset($imagePath))
                                        : asset('images/placeholder.png');
                                @endphp

                                <x-horizontalscrollbar 
                                    image="{{ $imageSrc }}"
                                    text="{{ $item->product_name }}"
                                    price="{{ $item->price }}"
                                    link="{{ route('product.show', $item->id) }}"
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
