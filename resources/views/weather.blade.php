<x-app-layout>

    <!-- ROW 1: LOCATION + WEATHER -->
    <div class="w-full sm:max-w-[1100px] mx-auto flex flex-wrap justify-center gap-6 p-6 mt-16">
        <!-- LOCATION CARD -->
        <div class="bg-white rounded-2xl shadow-md p-6 w-full sm:w-[320px]">
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
        <div class="bg-white rounded-2xl shadow-md p-6 w-full">
            <p class="text-base font-semibold mb-2">Outfit Recommendation</p>

            <!-- Event Filter -->
            <div class="mb-4">
                <label for="event-select" class="block text-sm font-medium text-gray-700">Event</label>
                <select id="event-select" name="event"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option>--select--</option>
                    <option value="concert">Concert</option>
                    <option value="school">School</option>
                    <option value="casual">Casual</option>
                </select>
            </div>

            <!-- Style Filter -->
            <div class="mb-4">
                <label for="style-select" class="block text-sm font-medium text-gray-700">Style</label>
                <select id="style-select" name="style"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option>--select--</option>
                    <option value="sporty">Sporty</option>
                    <option value="streetwear">Streetwear</option>
                    <option value="preppy">Preppy</option>
                </select>
            </div>

            <!-- Suggested Outfit -->
            <div class="mt-4">
                <p class="text-sm text-gray-500">Suggested Outfit:</p>
                <p id="suggested-outfit" class="text-base font-medium text-gray-800 mt-1">-- based on filters --</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
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
                            const now = new Date();

                            // Time & date
                            const timeStr = now.toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            document.getElementById('time-display').textContent = `As of: ${timeStr}`;

                            const dateStr = now.toLocaleDateString(undefined, {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });
                            document.getElementById('date-display').textContent = dateStr;

                            // Location
                            const city = data.city;
                            const state = data.state || 'your region';
                            document.getElementById('location-text').innerHTML = `<strong>${city}, ${state}</strong>`;

                            // Weather logic
                            const weather = data.weather;
                            const code = weather.weathercode;
                            const rain = data.rain_chance ?? 'N/A';
                            const temp = weather.temperature;

                            let shortLabel = 'clear';
                            let weatherIcon = 'clear.png';
                            if ([1, 2, 3].includes(code)) { weatherIcon = 'cloudy.png'; shortLabel = 'cloudy'; }
                            else if ([45, 48].includes(code)) { weatherIcon = 'fog.png'; shortLabel = 'fog'; }
                            else if ([51, 53, 55, 56, 57].includes(code)) { weatherIcon = 'drizzle.png'; shortLabel = 'drizzle'; }
                            else if ([61, 63, 65, 66, 67].includes(code)) { weatherIcon = 'rainy.png'; shortLabel = 'rainy'; }
                            else if ([71, 73, 75, 77].includes(code)) { weatherIcon = 'snow.png'; shortLabel = 'snow'; }
                            else if ([80, 81, 82].includes(code)) { weatherIcon = 'showers.png'; shortLabel = 'showers'; }
                            else if ([95, 96, 99].includes(code)) { weatherIcon = 'storm.png'; shortLabel = 'storm'; }
                            else if (code === 3) { shortLabel = 'overcast'; }

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
                            const conditionText = descriptions[code] || 'Unknown';
                            document.getElementById('weather-heading').innerHTML =
                                `<strong>THE WEATHER TODAY IS:</strong> ${conditionText}`;

                            let tempIcon = 'temperature.png';
                            let tempCategory = 'Moderate';
                            if (temp >= 30) {
                                tempIcon = 'hot.png';
                                tempCategory = 'Hot';
                            } else if (temp >= 26) {
                                tempIcon = 'hot.png';
                                tempCategory = 'Moderately Hot';
                            } else if (temp >= 21) {
                                tempIcon = 'temperature.png';
                                tempCategory = 'Moderate';
                            } else {
                                tempIcon = 'cold.png';
                                tempCategory = 'Cold';
                            }

                            document.getElementById('weather-icon').src = `/images/weather/${weatherIcon}`;
                            document.getElementById('condition-label').textContent = shortLabel;
                            document.getElementById('rain-label').textContent = `${rain}% precipitation`;
                            document.getElementById('temp-icon').src = `/images/weather/${tempIcon}`;
                            document.getElementById('temp-label').innerHTML = `${temp} °C<br>${tempCategory}`;
                        })
                        .catch(err => {
                            document.getElementById('weather-card').innerHTML = "<p>⚠️ Failed to load weather data.</p>";
                            console.error(err);
                        });
                },
                () => {
                    document.getElementById('weather-card').innerHTML = "<p>⚠️ Location permission denied.</p>";
                }
            );
        } else {
            document.getElementById('weather-card').innerHTML = "<p>⚠️ Geolocation not supported.</p>";
        }
    });
</script>
</x-app-layout>
