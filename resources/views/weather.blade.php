<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weather Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        header {
            background: white;
            padding: 20px;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            padding: 20px;
            gap: 20px;
            justify-content: center;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            width: 500px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            justify-items: center;
            align-items: center;
            margin-top: 10px;
        }
        .icon-block {
            text-align: center;
        }
        .icon-block img {
            width: 60px;
            height: auto;
        }
        .icon-label {
            font-size: 14px;
            color: #444;
            margin-top: 5px;
        }
        .card h2 {
            margin: 5px 0;
            font-size: 20px;
        }
        #weather-heading {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            align-items: center;
        }
        #weather-heading span {
            font-weight: normal;
        }
        #time-display {
            font-size: 14px;
            margin-bottom: 0;
        }
        #date-display {
            font-size: 16px;
            font-weight: bold;
            margin-top: 0;
        }

    </style>
</head>
<body>
    <header>NAVIGATION BAR</header>

    <div class="container">
    <!-- LEFT CARD (time + location) -->
    <div class="card" id="location-card">
        <p id="time-display">As of: --:--</p>
        <p id="date-display">--</p>
        <p>in</p>
        <h2 id="location-text">City, Region</h2>
    </div>

    <!-- RIGHT CARD (weather info) -->
    <div class="card" id="weather-card">
        <p id="weather-heading"><strong>THE WEATHER TODAY IS:</strong> <span id="description-text">Loading...</span></p>
        <div class="icon-grid">
            <div class="icon-block">
                <img id="weather-icon" src="" alt="Weather">
                <div class="icon-label" id="condition-label">--</div>
            </div>
            <div class="icon-block">
                <img src="/images/weather/humidity.png" alt="Humidity">
                <div class="icon-label" id="rain-label">--% precipitation</div>
            </div>
            <div class="icon-block">
                <img id="temp-icon" src="/images/weather/temperature.png" alt="Temperature">
                <div class="icon-label" id="temp-label">-- °C</div>
            </div>
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

                        // 🕒 Format time
                        const timeStr = now.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        document.getElementById('time-display').textContent = `As of: ${timeStr}`;

                        // 📅 Format date
                        const dateStr = now.toLocaleDateString(undefined, {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        document.getElementById('date-display').textContent = dateStr;

                        // 🌍 Location (use state instead of state_district)
                        const city = data.city;
                        const state = data.state || 'your region';
                        document.getElementById('location-text').innerHTML = `<strong>${city}, ${state}</strong>`;

                        // 🌤 Weather info
                        const weather = data.weather;
                        const code = weather.weathercode;
                        const rain = data.rain_chance ?? 'N/A';
                        const temp = weather.temperature;

                        // 💬 Short label and icon mapping
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

                        // 📘 Full description
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

                        // 🌡️ Temperature icon and label
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

                        // 🖼️ Inject into DOM
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


</body>
</html>
