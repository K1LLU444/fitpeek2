<x-app-layout>
        <div class="min-h-screen bg-gray-100">
            <nav x-data="{ open: false, profileOpen: false }" class="bg-white border-b border-gray-100 fixed top-0 z-50 w-full">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- Logo & Brand -->
            <div class="flex items-center space-x-3">
                <a href="/" class="flex items-center">
                    <img src="https://fitpeek.online/images/logo_fitpeek.svg" alt="FitPeek Logo" class="h-10 w-auto">
                    <span class="text-2xl font-semibold ml-2">FitPeek</span>
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="hidden sm:flex space-x-6 items-center">
                <a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-brown-100 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" href="https://fitpeek.online/dashboard">
    Home
</a>
                <a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-brown-100 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" href="https://fitpeek.online/createavatar">
    Your Avatar
</a>
                <a class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-brown-100 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" href="https://fitpeek.online/aboutus">
    About Us
</a>
                <a class="inline-flex items-center px-1 pt-1 border-b-2 border-brown-700 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-brown-700 transition duration-150 ease-in-out" href="https://fitpeek.online/weather">
    Recommendation
</a>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button @click="profileOpen = !profileOpen" class="flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-gray-100 hover:bg-gray-200 focus:outline-none">
                    <span class="truncate max-w-[120px] overflow-hidden text-ellipsis whitespace-nowrap block">
                        kyladeasas
                    </span>
                    <svg 
                        class="ml-1 h-4 w-4 transition-transform text-gray-600" 
                        :class="{ 'rotate-180': profileOpen }" 
                        xmlns="http://www.w3.org/2000/svg" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor" 
                        stroke-width="2">
                        <path 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            d="M19 9l-7 7-7-7" />
                    </svg>
                    </button>

                    <div x-show="profileOpen" @click.away="profileOpen = false" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-md z-50">
                    <div class="px-4 py-3 text-gray-700 w-[200px] max-w-[250px]">
                        <p class="text-sm font-semibold break-words">kyladeasas</p>
                        <p class="text-xs break-all text-gray-500 truncate" title="kyladeasas12@gmail.com">
                            kyladeasas12@gmail.com</p>
                    </div>
                        <div class="border-t border-gray-100">
                            <a class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out" href="https://fitpeek.online/profile">
    Profile
</a>
                            <form method="POST" action="https://fitpeek.online/logout">
                                <input type="hidden" name="_token" value="zDBWtykDxsKKeCoRxMZJgf49EgredgCsI0Ntowag" autocomplete="off">                                <a class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out" href="https://fitpeek.online/logout" onclick="event.preventDefault(); this.closest('form').submit();">
    Log Out
</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Hamburger -->
            <div class="sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="sm:hidden bg-white border-t border-gray-200 px-4 py-3">
        <a class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out" href="https://fitpeek.online/dashboard">
    Home
</a>
        <a class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out" href="https://fitpeek.online/createavatar">
    Your Avatar
</a>
        <a class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out" href="https://fitpeek.online/aboutus">
    About Us
</a>
        <a class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out" href="https://fitpeek.online/weather">
    Recommendation
</a>

        <!-- Mobile Profile -->
        <div class="mt-2 border-t border-gray-100 pt-2">
            <button @click="profileOpen = !profileOpen"
                class="w-full text-left text-sm text-gray-700 px-2 py-1 hover:bg-gray-100 rounded-md truncate"
                title="kyladeasas">
                kyladeasas
            </button>

            <div x-show="profileOpen" class="mt-1 space-y-1">
                <a class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out" href="https://fitpeek.online/profile">
    Profile
</a>
                <form method="POST" action="https://fitpeek.online/logout">
                    <input type="hidden" name="_token" value="zDBWtykDxsKKeCoRxMZJgf49EgredgCsI0Ntowag" autocomplete="off">                    <a class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out" href="https://fitpeek.online/logout" onclick="event.preventDefault(); this.closest('form').submit();">
    Log Out
</a>
                </form>
            </div>
        </div>
    </div>
</nav>
            <!-- Page Heading -->
            
            <!-- Page Content -->
            <main>
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
    <div class="grid grid-cols-1 lg:grid-cols-[45%_55%] gap-6">

        <!-- LEFT: Avatar/Overlay Box -->
        <div class="bg-white rounded-2xl shadow-md p-6 flex flex-col items-center justify-start gap-4 h-[700px] overflow-hidden">

            <!-- 🔘 Top Controls -->
            <div class="w-full flex flex-wrap items-center justify-center gap-4">

                <div class="flex items-center gap-1">
                    <label for="top-size-select" class="text-xs font-medium text-gray-600">Top:</label>
                    <select id="top-size-select" class="rounded-md border border-gray-300 text-xs w-[50px] px-2 py-1 shadow-sm text-left">
                        <option value="">--</option>
                    </select>
                </div>

                <div class="flex items-center gap-1">
                    <label for="bottom-size-select" class="text-xs font-medium text-gray-600">Bottom:</label>
                    <select id="bottom-size-select" class="rounded-md border border-gray-300 text-xs w-[50px] px-2 py-1 shadow-sm text-left">
                        <option value="">--</option>
                    </select>
                </div>

                <button id="toggle-avatar-view" class="bg-white hover:bg-gray-200 border border-gray-300 text-xs font-semibold px-2 py-1 rounded">Front</button>

                <button id="switch-overlay-order" class="bg-white hover:bg-gray-200 border border-gray-300 text-xs font-semibold px-2 py-1 rounded flex items-center justify-center" title="Switch Overlay Order">
                    <img id="overlay-icon" src="/images/weather/top-overlays.png" alt="Overlay Icon" class="w-4 h-4">
                </button>

                <button id="toggle-overlay-visibility" class="bg-white hover:bg-gray-200 border border-gray-300 text-xs font-semibold px-2 py-1 rounded flex items-center justify-center" title="Toggle Overlay Visibility">
                    <img id="visibility-icon" src="/images/weather/top-bottom.png" alt="Visibility Icon" class="w-4 h-4">
                </button>
                            <!-- 🟡 Dropdown Icon (Same Height & Style as Buttons) -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="bg-white hover:bg-gray-200 border border-gray-300 text-xs font-semibold px-2 py-1 rounded flex items-center justify-center">
                        <img src="/images/weather/background.png" alt="Dropdown Icon" class="w-4 h-4">
                    </button>

                <!-- Dropdown Panel -->
                <div x-show="open" @click.away="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-200 p-4 z-50 max-h-[360px] overflow-y-auto">

                        <div class="grid grid-cols-3 gap-4 text-center">
                            <!-- Sample App Item -->
                            <div class="image-grid-item flex flex-col items-center p-2 hover:bg-gray-100 rounded-lg cursor-pointer">
                                <img src="/images/background/school1.png" alt="Item 1" class="w-16 h-16 object-cover rounded-md">
                            </div>
                            <div class="image-grid-item flex flex-col items-center p-2 hover:bg-gray-100 rounded-lg cursor-pointer">
                                <img src="/images/background/city-day.png" alt="Item 2" class="w-16 h-16 object-cover rounded-md">
                            </div>
                            <div class="image-grid-item flex flex-col items-center p-2 hover:bg-gray-100 rounded-lg cursor-pointer">
                                <img src="/images/background/city-night.png" alt="Item 3" class="w-16 h-16 object-cover rounded-md">
                            </div>
                            <div class="image-grid-item flex flex-col items-center p-2 hover:bg-gray-100 rounded-lg cursor-pointer">
                                <img src="/images/background/garden-day.png" alt="Item 4" class="w-16 h-16 object-cover rounded-md">
                            </div>
                            <div class="image-grid-item flex flex-col items-center p-2 hover:bg-gray-100 rounded-lg cursor-pointer">
                                <img src="/images/background/rainy.png" alt="Item 5" class="w-16 h-16 object-cover rounded-md">
                            </div>
                            <div class="image-grid-item flex flex-col items-center p-2 hover:bg-gray-100 rounded-lg cursor-pointer">
                                <img src="/images/background/rainy-night.png" alt="Item 6" class="w-16 h-16 object-cover rounded-md">
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                document.querySelectorAll('.image-grid-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const avatarContainer = document.getElementById('avatar-container');
                        const img = this.querySelector('img');
                        const imageUrl = img.getAttribute('src');

                        // Check if already applied
                        const currentBg = avatarContainer.style.backgroundImage;
                        if (currentBg.includes(imageUrl)) {
                            avatarContainer.style.backgroundImage = '';
                            avatarContainer.classList.add('bg-gray-100');
                        } else {
                            avatarContainer.style.backgroundImage = `url('${imageUrl}')`;
                            avatarContainer.style.backgroundSize = 'cover';
                            avatarContainer.style.backgroundPosition = 'center';
                            avatarContainer.classList.remove('bg-gray-100');
                        }
                    });
                });
                </script>


            </div>

            <!-- Avatar Display + Overlays -->
            <div id="avatar-container"
                data-avatar-height="{{ $avatar->height }}"
                data-height-unit="{{ $avatar->unit }}"
                class="relative w-full h-[600px] p-6 bg-gray-100 rounded-xl flex items-center justify-center shadow overflow-visible">

                <img id="avatar-image" src="" data-front="" data-back="" alt="Avatar Overlay"
                    class="max-h-full object-contain mx-auto pointer-events-none transition-all duration-300">

                <img id="top-overlay" src="" alt="Shirt Overlay"
                    class="absolute left-0 right-0 mx-auto object-contain pointer-events-none transition-all duration-300 ease-in-out border border-xl"
                    style="display: none;">

                <img id="bottom-overlay" src="" alt="Pants Overlay"
                    class="absolute left-0 right-0 mx-auto object-fill pointer-events-none transition-all duration-300 ease-in-out border border-xl"
                    style="display: none;">

                <button id="upper-left-avatar" class="absolute left-3 top-20 text-3xl font-bold text-gray-700 hover:text-black">❮</button>
                <button id="upper-right-avatar" class="absolute right-3 top-20 text-3xl font-bold text-gray-700 hover:text-black">❯</button>
                <button id="lower-left-avatar" class="absolute left-3 bottom-20 text-3xl font-bold text-gray-700 hover:text-black">❮</button>
                <button id="lower-right-avatar" class="absolute right-3 bottom-20 text-3xl font-bold text-gray-700 hover:text-black">❯</button>
            </div>
        </div>
        <!-- RIGHT: Filter + Product Cards -->
        <div class="bg-white rounded-2xl shadow-md p-6 w-full overflow-y-auto max-h-[700px]">
            <div id="filter-form" class="mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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

            <div class="flex flex-wrap gap-4 mt-4">
                <button id="toggle-unit-btn"
                    class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-xs px-3 py-1 rounded">
                    Switch to Inches
                </button>
                <button id="toggle-weather-btn"
                    class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 text-xs px-3 py-1 rounded">
                    Ignore Weather
                </button>
            </div>

            <!-- Product Details -->
            <div id="top-product" class="border-t pt-4 mb-6">
                <p class="text-sm font-semibold text-gray-800">Top:</p>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-bold text-md"></p>
                        <p class="text-sm text-gray-600"></p>
                        <p class="text-sm text-gray-500 mb-2"></p>
                        <p class="text-sm text-indigo-500"><a href="#" target="_blank"></a></p>
                    </div>
                </div>
            </div>

            <div id="bottom-product" class="border-t pt-4">
                <p class="text-sm font-semibold text-gray-800">Bottom:</p>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-bold text-md"></p>
                        <p class="text-sm text-gray-600"></p>
                        <p class="text-sm text-gray-500 mb-2"></p>
                        <p class="text-sm text-indigo-500"><a href="#" target="_blank"></a></p>
                    </div>
                </div>
            </div>
        </div>
                <script>
        // ✅ Arrow navigation setup – clean and stable
        function setupArrowNavigation() {
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
        }

        // ⏳ Wait for DOM to be ready
        document.addEventListener("DOMContentLoaded", setupArrowNavigation);
        </script>
    </div>
</div>



<!-- 🔁 Toggle Front/Back Script -->
<script>
    let showingFront = true;

    document.addEventListener("DOMContentLoaded", function () {
        const toggleButton = document.getElementById('toggle-avatar-view');
        const avatarImage = document.getElementById('avatar-image');


        toggleButton.addEventListener('click', () => {
            showingFront = !showingFront;
            const avatarImage = document.getElementById('avatar-image');
            avatarImage.src = showingFront ? avatarImage.dataset.front : avatarImage.dataset.back;
            toggleButton.textContent = showingFront ? 'Front' : 'Back';
            updateUI(); // 🔄 Update overlays
            applyOverlayVisibility();
        });
    let avatarLoaded = false;
    let dataLoaded = false;

    const tryUpdate = () => {
        if (avatarLoaded && dataLoaded) {
            setTimeout(updateUI, 50); // Give DOM a slight delay to layout
        }
    };

    // ✅ Detect when avatar image has loaded
    if (avatarImage.complete) {
        avatarLoaded = true;
        tryUpdate();
    } else {
        avatarImage.onload = () => {
            avatarLoaded = true;
            tryUpdate();
        };
    }

    // ✅ Wait for product data to be ready
    const checkProductsReady = setInterval(() => {
        if (typeof topProducts !== 'undefined' && topProducts.length > 0) {
            dataLoaded = true;
            clearInterval(checkProductsReady);
            tryUpdate();
        }
    }, 50); // Check every 50ms
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
let selectedTopSize = "";
let selectedBottomSize = "";

 // "top" or "bottom"



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
    document.getElementById("top-size-select").addEventListener("change", (e) => {
    selectedTopSize = e.target.value;
    updateUI();
    });
    document.getElementById("bottom-size-select").addEventListener("change", (e) => {
        selectedBottomSize = e.target.value;
        updateUI();
    });
    let overlayOnTop = "top";
    document.getElementById("switch-overlay-order").addEventListener("click", () => {
        overlayOnTop = overlayOnTop === "top" ? "bottom" : "top";

        // Toggle z-index
        const topOverlay = document.getElementById("top-overlay");
        const bottomOverlay = document.getElementById("bottom-overlay");

        if (overlayOnTop === "top") {
            topOverlay.style.zIndex = "20";
            bottomOverlay.style.zIndex = "10";
            document.getElementById("overlay-icon").src = "/images/weather/top-overlays.png";
        } else {
            topOverlay.style.zIndex = "10";
            bottomOverlay.style.zIndex = "20";
            document.getElementById("overlay-icon").src = "/images/weather/top-overlays.png";
        }
    });

let overlayVisibilityState = 0; // 1 = both visible, 2 = top only, 0 = bottom only
function applyOverlayVisibility() {
    const topOverlay = document.getElementById("top-overlay");
    const bottomOverlay = document.getElementById("bottom-overlay");
    const icon = document.getElementById("visibility-icon");

    const topSrc = topOverlay.src || "";
    const bottomSrc = bottomOverlay.src || "";

    const topValid = topSrc.trim() !== "" && !topSrc.includes("undefined");
    const bottomValid = bottomSrc.trim() !== "" && !bottomSrc.includes("undefined");

    if (overlayVisibilityState === 0) {
        // 1️⃣ Both visible (if valid)
        topOverlay.style.display = topValid ? "block" : "none";
        bottomOverlay.style.display = bottomValid ? "block" : "none";
        icon.src = "/images/weather/top-bottom.png";
    } else if (overlayVisibilityState === 1) {
        // 2️⃣ Only top visible (if valid)
        topOverlay.style.display = topValid ? "block" : "none";
        bottomOverlay.style.display = "none";
        icon.src = "/images/weather/top-only.png";
    } else if (overlayVisibilityState === 2) {
        // 3️⃣ Only bottom visible (if valid)
        topOverlay.style.display = "none";
        bottomOverlay.style.display = bottomValid ? "block" : "none";
        icon.src = "/images/weather/bottom-only.png";
    }
}

document.getElementById("toggle-overlay-visibility").addEventListener("click", () => {
    overlayVisibilityState = (overlayVisibilityState + 1) % 3;
    applyOverlayVisibility();
});




});
function updateAvatarImageBasedOnPose(pose) {
    return fetch('/get-avatar-path', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ pose: pose })
    })
    .then(res => res.json())
    .then(data => {
        const avatarImage = document.getElementById("avatar-image");
        avatarImage.dataset.front = data.front;
        avatarImage.dataset.back = data.back;

        // ✅ Force refresh
        const newSrc = (showingFront ? data.front : data.back) + '?v=' + Date.now();
        avatarImage.src = newSrc;

        console.log("✅ Avatar src set to:", newSrc);
    })
    .catch(err => {
        console.error("❌ Failed to load avatar image paths:", err);
    });
}




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
        setTimeout(updateUI, 50);
    });
}

async function updateUI() {
    const top = topProducts[topIndex];
    const bottom = bottomProducts[bottomIndex];
    const isBack = !showingFront;

    const pose = top?.pose === 'low' ? 'low' : 'high';
    await updateAvatarImageBasedOnPose(pose);

    const avatarImage = document.getElementById("avatar-image");
    const avatarContainer = document.getElementById("avatar-container");

    if (avatarImage && avatarImage.dataset) {
        const newSrc = (showingFront ? avatarImage.dataset.front : avatarImage.dataset.back) + '?v=' + Date.now();
        avatarImage.src = newSrc;
    }

    const topOverlay = document.getElementById("top-overlay");
    const bottomOverlay = document.getElementById("bottom-overlay");
    const avatarPixelHeight = avatarImage.clientHeight || 480;

    const rawHeight = parseFloat(avatarContainer.dataset.avatarHeight);
    const rawUnit = avatarContainer.dataset.heightUnit || "cm";
    const normalizedUnit = rawUnit.trim().toLowerCase();
    const avatarHeightInCm = (normalizedUnit === "inches" || normalizedUnit === "in") ? rawHeight * 2.54 : rawHeight;

    // Align overlay position relative to avatar image
    const imageBox = avatarImage.getBoundingClientRect();
    const containerBox = avatarContainer.getBoundingClientRect();
    const topOffsetPx = imageBox.top - containerBox.top;

    const neckOffsetPx = topOffsetPx + 43;
    const waistOffsetPx = topOffsetPx + 132;

    document.getElementById("top-product").innerHTML = top ? renderProduct('Top', top) : '<p>No top found.</p>';
    document.getElementById("bottom-product").innerHTML = bottom ? renderProduct('Bottom', bottom) : '<p>No bottom found.</p>';

    const topSizeSelect = document.getElementById("top-size-select");
    const bottomSizeSelect = document.getElementById("bottom-size-select");

    if (top && top.size_chart) {
        const sizeChart = top.size_chart;
        topSizeSelect.innerHTML = "";
        Object.keys(sizeChart).forEach(size => {
            const option = document.createElement("option");
            option.value = size;
            option.textContent = size;
            topSizeSelect.appendChild(option);
        });
        if (!selectedTopSize || !(selectedTopSize in sizeChart)) {
            selectedTopSize = Object.keys(sizeChart)[0];
        }
        topSizeSelect.value = selectedTopSize;
    }

    if (bottom && bottom.size_chart) {
        const sizeChart = bottom.size_chart;
        bottomSizeSelect.innerHTML = "";
        Object.keys(sizeChart).forEach(size => {
            const option = document.createElement("option");
            option.value = size;
            option.textContent = size;
            bottomSizeSelect.appendChild(option);
        });
        if (!selectedBottomSize || !(selectedBottomSize in sizeChart)) {
            selectedBottomSize = Object.keys(sizeChart)[0];
        }
        bottomSizeSelect.value = selectedBottomSize;
    }

    // 🔄 Top overlay
    if (top && top.size_chart && selectedTopSize) {
        const topLengthCm = top.size_chart[selectedTopSize]?.length || 30;
        const pixelHeight = (topLengthCm / avatarHeightInCm) * avatarPixelHeight;

        const rawSrc = isBack ? top.back_image : top.front_image;
        const fixedSrc = rawSrc?.startsWith("/storage") ? location.origin + rawSrc : rawSrc;

        topOverlay.src = fixedSrc;
        topOverlay.style.display = "block";
        topOverlay.style.height = `${pixelHeight}px`;
        topOverlay.style.top = `${neckOffsetPx}px`;
        topOverlay.style.clipPath = "none";
    } else {
        topOverlay.src = "";
        topOverlay.style.display = "none";
    }

// 🔄 Bottom overlay scaling based on size_chart.length and natural image size
if (bottom && bottom.size_chart && selectedBottomSize) {
    const selected = bottom.size_chart[selectedBottomSize];
    const sizeChart = bottom.size_chart;

    // 📏 Find the base (smallest) length from size chart
    const allLengths = Object.values(sizeChart).map(s => s.length || 1);
    const baseLengthCm = Math.min(...allLengths);
    const selectedLengthCm = selected.length || baseLengthCm;

    // 🧮 Scale factor relative to base length
    const scaleFactor = selectedLengthCm / baseLengthCm;

    // ✅ Reference image source
    // ✅ Reference image source
    const rawSrc = isBack ? bottom.back_image : bottom.front_image;
    console.log("🖼 Raw bottom image source:", rawSrc);

    const fixedSrc = rawSrc?.startsWith("/storage") ? location.origin + rawSrc : rawSrc;
    console.log("✅ Resolved bottom image source:", fixedSrc);


    // 🖼 Load the image to get natural size
    const img = new Image();
    img.src = fixedSrc;

    img.onload = () => {
        const aspectRatio = img.naturalWidth / img.naturalHeight;

        // 🟢 Convert length from cm → px using avatar scale
        const heightPx = (selectedLengthCm / avatarHeightInCm) * avatarPixelHeight;
        const widthPx = heightPx * aspectRatio;

        console.log("👖 Bottom Length CM:", selectedLengthCm);
        console.log("📐 Calculated Height (px):", heightPx);
        console.log("📐 Aspect Ratio:", aspectRatio);
        console.log("📏 Width (px):", widthPx);

        bottomOverlay.src = fixedSrc;
        bottomOverlay.style.display = "block";
        bottomOverlay.style.height = `${heightPx}px`;
        bottomOverlay.style.width = `${widthPx}px`;
        bottomOverlay.style.top = `${waistOffsetPx}px`;
        bottomOverlay.style.left = "0";
        bottomOverlay.style.right = "0";
        bottomOverlay.style.margin = "0 auto";
        bottomOverlay.style.clipPath = "none";

        // ✅ Re-apply visibility mode
        applyOverlayVisibility?.();
    };
} else {
    bottomOverlay.src = "";
    bottomOverlay.style.display = "none";
}
    
}









function getFirstSize(sizeChartObj) {
    try {
        const firstKey = Object.keys(sizeChartObj)[0];
        return sizeChartObj[firstKey];
    } catch {
        return null;
    }
}

function renderProduct(label, product) {
    return `
        <p class="text-sm font-semibold text-gray-800">${label}:</p>
        <div class="flex justify-between items-start gap-4">
            <div>
                <p class="font-bold text-md">${product.product_name}</p>
                <p class="text-sm text-gray-600">₱${parseFloat(product.price).toFixed(2)}</p>
                <p class="text-sm text-gray-500 mb-2">${product.description}</p>

                ${renderSizeChart(product.size_chart)} <!-- ⬅️ moved here -->

                <p class="text-sm text-indigo-500 mt-2">
                    <a href="${product.product_link}" target="_blank">Visit site ↗</a>
                </p>
            </div>
        </div>`;
}
function renderSizeChart(jsonStr) {
    try {
        const data = typeof jsonStr === 'string' ? JSON.parse(jsonStr) : jsonStr;

        if (!data || typeof data !== 'object' || Object.keys(data).length === 0) {
            return '<p class="text-sm text-red-500">⚠️ No size chart available</p>';
        }

        const sizes = Object.keys(data);

        const widthRow = sizes.map(size => {
            const val = data[size]?.width ?? '--';
            return `<td class="border px-2 py-1">${formatUnit(val)}</td>`;
        }).join('');

        const lengthRow = sizes.map(size => {
            const val = data[size]?.length ?? '--';
            return `<td class="border px-2 py-1">${formatUnit(val)}</td>`;
        }).join('');

        return `<table class="text-sm border border-gray-500 text-center mt-2">
                    <thead>
                        <tr>
                            <th class="border px-2 py-1">Size</th>
                            ${sizes.map(size => `<th class="border px-2 py-1">${size}</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-2 py-1 font-semibold">Width</td>
                            ${widthRow}
                        </tr>
                        <tr>
                            <td class="border px-2 py-1 font-semibold">Length</td>
                            ${lengthRow}
                        </tr>
                    </tbody>
                </table>`;
    } catch (e) {
        console.warn('❌ Failed to parse size_chart:', jsonStr, e);
        return '<p class="text-sm text-red-500">⚠️ Invalid size chart format</p>';
    }
}

function formatUnit(value) {
    if (value === '--' || value == null || isNaN(value)) return '--';
    const num = parseFloat(value);
    return currentUnit === 'in' ? (num / 2.54).toFixed(2) : num.toFixed(2);
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

<script>
let currentUnit = "cm"; // Default
let ignoreWeather = false;

document.getElementById("toggle-unit-btn").addEventListener("click", () => {
    currentUnit = currentUnit === "cm" ? "in" : "cm";
    document.getElementById("toggle-unit-btn").textContent = currentUnit === "cm" ? "Switch to Inches" : "Switch to CM";
    updateUI(); // 🔄 Re-render with updated unit
});

document.getElementById("toggle-weather-btn").addEventListener("click", () => {
    ignoreWeather = !ignoreWeather;
    weatherCondition = ignoreWeather ? "" : getWeatherCategory(
        document.getElementById("condition-label").textContent.toLowerCase(),
        parseFloat(document.getElementById("temp-label").textContent)
    );

    document.getElementById("toggle-weather-btn").textContent = ignoreWeather ? "Use Weather" : "Ignore Weather";
    fetchRecommendations();
});
</script>

            </main>
        </div>
        <x-footer />
</x-app-layout>