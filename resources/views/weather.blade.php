<x-app-layout>

    <!-- ROW 1: LOCATION + WEATHER -->
    <div class="w-full sm:max-w-[1100px] mx-auto flex flex-wrap justify-center gap-6 p-6 mt-16">
        <!-- LOCATION CARD -->
        <div class="bg-white rounded-2xl shadow-md p-6 w-full sm:w-[320px]" id="weather-card">
            <p id="time-display" class="text-sm">As of: --:--</p>
            <p id="date-display" class="text-base font-semibold mt-0 mb-2">--</p>
            <p>in</p>
            <h2 id="location-text" class="text-xl font-bold mt-2">City, Region</h2>
        </div>

        <!-- WEATHER CARD -->
        <div class="bg-white rounded-2xl shadow-md p-6 flex-1 min-w-[320px]">
            <p id="weather-heading" class="flex flex-wrap items-center gap-2 text-base font-semibold">
                <strong>THE WEATHER TODAY IS:</strong> <span id="description-text" class="font-normal">Loading...</span>
            </p>

            <div class="grid grid-cols-3 gap-6 justify-items-center items-center mt-4">
                <div class="text-center">
                    <img id="weather-icon" src="" alt="Weather" class="w-14 h-auto">
                    <div class="text-sm text-gray-700 mt-1" id="condition-label">--</div>
                </div>
                <div class="text-center">
                    <img src="/images/weather/humidity.png" alt="Rain" class="w-14 h-auto">
                    <div class="text-sm text-gray-700 mt-1" id="rain-label">--% precipitation</div>
                </div>
                <div class="text-center">
                    <img id="temp-icon" src="/images/weather/temperature.png" alt="Temp" class="w-14 h-auto">
                    <div class="text-sm text-gray-700 mt-1" id="temp-label">-- °C</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 2: OUTFIT RECOMMENDATION -->
    <div class="w-full sm:max-w-[1100px] mx-auto p-6">
        <div class="flex flex-col lg:flex-row gap-6">

            <!-- LEFT: Filter + Product Cards -->
        <div class="bg-white rounded-2xl shadow-md p-6 w-full lg:w-1/2">
            <!-- Form wrapper -->
            <div id="filter-form" class="mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Events -->
                    <div>
                        <label for="event-select" class="block text-sm font-medium text-gray-700 mb-1">Events:</label>
                        <select id="event-select" name="event"
                            class="w-full rounded-md border border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2">
                            <option>--select--</option>
                            <option value="Casual Outing">Casual Outing</option>
                            <option value="Work">Work</option>
                            <option value="School">School</option>
                            <option value="Fitness">Fitness</option>
                            <option value="Special Occasion">Special Occasion</option>
                        </select>
                    </div>

                    <!-- Styles -->
                    <div>
                        <label for="style-select" class="block text-sm font-medium text-gray-700 mb-1">Styles:</label>
                        <select id="style-select" name="style"
                            class="w-full rounded-md border border-gray-300 shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2">
                            <option>--select--</option>
                            <option value="Casual">Casual</option>
                            <option value="Smart Casual">Smart Casual</option>
                            <option value="Athleisure">Athleisure</option>
                            <option value="Streetwear">Streetwear</option>
                            <option value="Breezy">Breezy</option>
                        </select>
                    </div>
                </div>
            </div>

        <!-- Top Product -->
        <div id="top-product" class="border-t pt-4 mb-6">
            <p class="text-sm font-semibold text-gray-800">Top:</p>
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold text-md"></p>
                    <p class="text-sm text-gray-600"></p>
                    <p class="text-sm text-gray-500 mb-2"></p>
                    <p class="text-sm text-indigo-500"><a href="#" target="_blank"></a></p>
                </div>
                <table class="text-sm border border-gray-500 text-center">
                    <thead class="bg-gray-100 text-xs">
                        <tr>
                            <th class="border px-2 py-1"></th>
                            <th class="border px-2 py-1"></th>
                            <th class="border px-2 py-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td></tr>
                        <tr><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td></tr>
                        <tr><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom Product -->
        <div id="bottom-product" class="border-t pt-4">
            <p class="text-sm font-semibold text-gray-800">Bottom:</p>
            <div class="flex justify-between items-start">
                <div>
                    <p class="font-bold text-md"></p>
                    <p class="text-sm text-gray-600"></p>
                    <p class="text-sm text-gray-500 mb-2"></p>
                    <p class="text-sm text-indigo-500"><a href="#" target="_blank"></a></p>
                </div>
                <table class="text-sm border border-gray-500 text-center">
                    <thead class="bg-gray-100 text-xs">
                        <tr>
                            <th class="border px-2 py-1"></th>
                            <th class="border px-2 py-1"></th>
                            <th class="border px-2 py-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td></tr>
                        <tr><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td></tr>
                        <tr><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td><td class="border px-2 py-1"></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


            <!-- RIGHT: Avatar/Overlay Box -->
            <div class="bg-gray-100 rounded-2xl shadow-inner w-full lg:w-1/2 p-6 flex items-center justify-center">
    <div class="relative w-full h-[480px] bg-white rounded-xl flex items-center justify-center shadow overflow-visible">

        <!-- 🔄 Switch Toggle: Front / Back -->
        <button id="toggle-avatar-view"
            class="absolute top-4 left-4 bg-gray-200 hover:bg-gray-300 text-xs font-semibold px-3 py-1 rounded">
            Show Back
        </button>

        @if($avatar && $avatar->avatar_cleaned_front_path)
            <img id="avatar-image"
                src="{{ asset($avatar->avatar_cleaned_front_path) }}"
                data-front="{{ asset($avatar->avatar_cleaned_front_path) }}"
                data-back="{{ asset($avatar->avatar_cleaned_back_path) }}"
                alt="Avatar Overlay"
                class="max-h-full transition-all duration-300 object-contain">

            <img id="top-overlay"
                src=""
                alt="Shirt Overlay"
                class="absolute top-0 left-0 max-h-full object-contain pointer-events-none">

            <img id="bottom-overlay"
                src=""
                alt="Pants Overlay"
                class="absolute top-0 left-0 max-h-full object-contain pointer-events-none">

        @else
            <div class="text-gray-400">No avatar found.</div>
        @endif

        <!-- UPPER-LEFT ARROW -->
        <button id="upper-left-avatar"
            class="absolute left-3 top-20 text-3xl font-bold text-gray-700 hover:text-black">
            ❮
        </button>

        <!-- LOWER-LEFT ARROW -->
        <button id="lower-left-avatar"
            class="absolute left-3 bottom-20 text-3xl font-bold text-gray-700 hover:text-black">
            ❮
        </button>

        <!-- UPPER-RIGHT ARROW -->
        <button id="upper-right-avatar"
            class="absolute right-3 top-20 text-3xl font-bold text-gray-700 hover:text-black">
            ❯
        </button>

        <!-- LOWER-RIGHT ARROW -->
        <button id="lower-right-avatar"
            class="absolute right-3 bottom-20 text-3xl font-bold text-gray-700 hover:text-black">
            ❯
        </button>
    </div>
</div>

<!-- 🔁 Toggle Front/Back Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleButton = document.getElementById('toggle-avatar-view');
        const avatarImage = document.getElementById('avatar-image');


        toggleButton.addEventListener('click', () => {
            showingFront = !showingFront;
            const avatarImage = document.getElementById('avatar-image');
            avatarImage.src = showingFront ? avatarImage.dataset.front : avatarImage.dataset.back;
            toggleButton.textContent = showingFront ? 'Show Back' : 'Show Front';
            updateUI(); // 🔄 Update overlays
        });
    });
    // 🔼 UPPER ARROWS – browse TOP products only
    document.getElementById("upper-left-avatar").addEventListener("click", () => {
        if (topIndex > 0) {
            topIndex--;
            updateUI();
        }
    });
    document.getElementById("upper-right-avatar").addEventListener("click", () => {
        if (topIndex < topProducts.length - 1) {
            topIndex++;
            updateUI();
        }
    });

    // 🔽 LOWER ARROWS – browse BOTTOM products only
    document.getElementById("lower-left-avatar").addEventListener("click", () => {
        if (bottomIndex > 0) {
            bottomIndex--;
            updateUI();
        }
    });
    document.getElementById("lower-right-avatar").addEventListener("click", () => {
        if (bottomIndex < bottomProducts.length - 1) {
            bottomIndex++;
            updateUI();
        }
    });

</script>

        </div>
    </div>


    <script>
let weatherCondition = "";
let topIndex = 0;
let bottomIndex = 0;
let topProducts = [];
let bottomProducts = [];
let showingFront = true;

document.addEventListener("DOMContentLoaded", () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            fetch('/get-weather', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ latitude: lat, longitude: lon })
            })
            .then(res => res.json())
            .then(data => {
                const weather = data.weather;
                const code = weather.weathercode;
                const temp = weather.temperature;
                const rain = data.rain_chance ?? 'N/A';
                const shortLabel = getShortLabel(code);
                weatherCondition = getWeatherCategory(shortLabel, temp);

                // Update DOM
                document.getElementById('condition-label').textContent = shortLabel;
                document.getElementById('rain-label').textContent = `${rain}% precipitation`;
                document.getElementById('temp-label').innerHTML = `${temp} °C<br>${getTempCategory(temp)}`;
                document.getElementById('weather-icon').src = `/images/weather/${getWeatherIcon(code)}`;
                document.getElementById('temp-icon').src = `/images/weather/${getTempIcon(temp)}`;

                document.getElementById('weather-heading').innerHTML =
                    `<strong>THE WEATHER TODAY IS:</strong> ${getConditionText(code)}`;
                document.getElementById('time-display').textContent = `As of: ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
                document.getElementById('date-display').textContent = new Date().toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
                document.getElementById('location-text').innerHTML = `<strong>${data.city}, ${data.state || 'your region'}</strong>`;

                fetchRecommendations();
            });
        });
    }

    document.getElementById("event-select").addEventListener("change", fetchRecommendations);
    document.getElementById("style-select").addEventListener("change", fetchRecommendations);

    // 🔁 Top arrows
    document.getElementById("upper-left-avatar").addEventListener("click", () => {
        if (topIndex > 0) topIndex--;
        updateUI();
    });
    document.getElementById("upper-right-avatar").addEventListener("click", () => {
        if (topIndex < topProducts.length - 1) topIndex++;
        updateUI();
    });

    // 🔁 Bottom arrows
    document.getElementById("lower-left-avatar").addEventListener("click", () => {
        if (bottomIndex > 0) bottomIndex--;
        updateUI();
    });
    document.getElementById("lower-right-avatar").addEventListener("click", () => {
        if (bottomIndex < bottomProducts.length - 1) bottomIndex++;
        updateUI();
    });
});

function fetchRecommendations() {
    const event = document.getElementById("event-select").value;
    const style = document.getElementById("style-select").value;

    fetch("/fetch-recommendations", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ weather: weatherCondition, event, style })
    })
    .then(res => res.json())
    .then(data => {
        topProducts = data.top || [];
        bottomProducts = data.bottom || [];
        topIndex = 0;
        bottomIndex = 0;
        updateUI();
    });
}

function updateUI() {
    const top = topProducts[topIndex];
    const bottom = bottomProducts[bottomIndex];

    // Render product info cards
    document.getElementById("top-product").innerHTML = top ? renderProduct('Top', top) : '<p>No top found.</p>';
    document.getElementById("bottom-product").innerHTML = bottom ? renderProduct('Bottom', bottom) : '<p>No bottom found.</p>';

    // Get overlay elements
    const topOverlay = document.getElementById("top-overlay");
    const bottomOverlay = document.getElementById("bottom-overlay");

    // Get current view (front or back)
    const isBack = !showingFront;

    // Assign overlay images based on current view
    if (top) {
        topOverlay.src = isBack ? top.back_image : top.front_image;
        topOverlay.style.display = "block";
    } else {
        topOverlay.src = "";
        topOverlay.style.display = "none";
    }

    if (bottom) {
        bottomOverlay.src = isBack ? bottom.back_image : bottom.front_image;
        bottomOverlay.style.display = "block";
    } else {
        bottomOverlay.src = "";
        bottomOverlay.style.display = "none";
    }
    console.log("🔍 Showing front?", showingFront);
console.log("👕 Top overlay image:", top ? (isBack ? top.back_image : top.front_image) : "none");
console.log("👖 Bottom overlay image:", bottom ? (isBack ? bottom.back_image : bottom.front_image) : "none");

}



function renderProduct(label, product) {
    return `
        <p class="text-sm font-semibold text-gray-800">${label}:</p>
        <div class="flex justify-between items-start">
            <div>
                <p class="font-bold text-md">${product.product_name}</p>
                <p class="text-sm text-gray-600">₱${parseFloat(product.price).toFixed(2)}</p>
                <p class="text-sm text-gray-500 mb-2">${product.description}</p>
                <p class="text-sm text-indigo-500"><a href="${product.product_link}" target="_blank">Visit site ↗</a></p>
            </div>
            ${renderSizeChart(product.size_chart)}
        </div>`;
}

function renderSizeChart(jsonStr) {
    try {
        const data = JSON.parse(jsonStr);
        let rows = '';
        for (const size in data) {
            const { width, length } = data[size];
            rows += `<tr>
                        <td class="border px-2 py-1">${size}</td>
                        <td class="border px-2 py-1">${width}</td>
                        <td class="border px-2 py-1">${length}</td>
                     </tr>`;
        }
        return `<table class="text-sm border border-gray-500 text-center">
                    <thead><tr><th></th><th>width</th><th>length</th></tr></thead>
                    <tbody>${rows}</tbody>
                </table>`;
    } catch {
        return '<p>Invalid size chart</p>';
    }
}

// Helpers
function getShortLabel(code) {
    if ([1, 2, 3].includes(code)) return 'cloudy';
    if ([45, 48].includes(code)) return 'fog';
    if ([51, 53, 55, 56, 57].includes(code)) return 'drizzle';
    if ([61, 63, 65, 66, 67].includes(code)) return 'rainy';
    if ([71, 73, 75, 77].includes(code)) return 'snow';
    if ([80, 81, 82].includes(code)) return 'showers';
    if ([95, 96, 99].includes(code)) return 'storm';
    return 'clear';
}

function getWeatherIcon(code) {
    const map = {
        'cloudy': 'cloudy.png', 'fog': 'fog.png', 'drizzle': 'drizzle.png',
        'rainy': 'rainy.png', 'snow': 'snow.png', 'showers': 'showers.png',
        'storm': 'storm.png', 'clear': 'clear.png'
    };
    return map[getShortLabel(code)] || 'clear.png';
}

function getConditionText(code) {
    const descriptions = {
        0: "Clear sky", 1: "Mainly clear", 2: "Partly cloudy", 3: "Overcast",
        45: "Fog", 48: "Depositing fog", 51: "Light drizzle", 53: "Moderate drizzle",
        55: "Dense drizzle", 61: "Slight rain", 63: "Moderate rain", 65: "Heavy rain",
        66: "Freezing rain", 67: "Freezing heavy rain", 71: "Slight snow",
        73: "Moderate snow", 75: "Heavy snow", 77: "Snow grains",
        80: "Light rain showers", 81: "Moderate rain showers", 82: "Violent rain showers",
        85: "Slight snow showers", 86: "Heavy snow showers",
        95: "Thunderstorm", 96: "Thunderstorm with hail", 99: "Thunderstorm with heavy hail"
    };
    return descriptions[code] || 'Unknown';
}

function getTempCategory(temp) {
    if (temp >= 30) return 'Hot';
    if (temp >= 26) return 'Moderately Hot';
    if (temp >= 21) return 'Moderate';
    return 'Cold';
}

function getTempIcon(temp) {
    if (temp >= 26) return 'hot.png';
    if (temp >= 21) return 'temperature.png';
    return 'cold.png';
}

function getWeatherCategory(label, temp) {
    if (["rainy", "storm", "showers", "drizzle"].includes(label)) return "Rainy";
    if (["clear", "hot", "cloudy"].includes(label) && temp >= 26) return "Hot";
    return "Cool";
}
</script>


</x-app-layout>
