<x-app-layout>
    <body>
        <div class="w-full mt-[80px]">
            <!-- About Us Section -->
            <div class="relative w-full mt-8">
                <img src="{{ asset('images/aboutbg.jpg') }}" 
                    alt="About Us Background" 
                    class="w-screen mb-10">

                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-black text-4xl md:text-6xl font-bold drop-shadow-lg">
                    About Us
                </div>
            </div>

            <!-- Vision Section -->
            <section class="flex flex-col md:flex-row items-center bg-[#9B7C69] text-white p-6 md:p-10 bg-secondary-color">
                <!-- Left Image -->
                <div class="w-full md:w-1/2 flex justify-center mb-6 md:mb-0">
                    <div class="bg-gray-200 w-[300px] h-[200px] flex items-center justify-center">
                        <img class="" alt="Image">
                    </div>
                </div>
                <!-- Right Text -->
                <div class="w-full md:w-1/2 px-4">
                    <h2 class="text-3xl font-bold mb-3">Vision</h2>
                    <p class="text-sm leading-relaxed">
                        To revolutionize the online shopping experience by developing an innovative, AI-powered virtual fitting room that enhances user confidence, reduces clothing return rates, and promotes sustainable e-commerce practices.
                    </p>
                </div>
            </section>

            <!-- Mission Section -->
            <section class="flex flex-col md:flex-row-reverse items-center bg-[#515051] text-white p-6 md:p-10">
                <!-- Right Image -->
                <div class="w-full md:w-1/2 flex justify-center mb-6 md:mb-0">
                    <div class="bg-gray-200 w-[300px] h-[200px] flex items-center justify-center">
                        <img class="" alt="Image">
                    </div>
                </div>
                <!-- Left Text -->
                <div class="w-full md:w-1/2 px-4">
                    <h2 class="text-3xl font-bold mb-3">Mission</h2>
                    <p class="text-sm leading-relaxed">
                        Our mission is to create a user-centric Progressive Web Application (PWA) that integrates advanced technologies such as AI-driven clothing size recommendations, 2D avatar generation, and real-time clothing overlay. By leveraging web scraping, OpenCV, and machine learning, we aim to provide an accurate and immersive virtual try-on experience. This research seeks to improve online shopping convenience, minimize size mismatches, and contribute to a more sustainable fashion industry by reducing unnecessary returns and waste.
                    </p>
                </div>
            </section>
        </div>

        <!-- Our Team Section -->
        <div class="w-full py-16 bg-gray-100 px-4">
            <h2 class="text-3xl font-bold text-center mb-10">Our Team</h2>
            <div class="max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-2 gap-6">
                <x-teamcard 
                image="{{ asset('images/jecxer-1x1.png') }}" 
                name="Jecxer Bathan" 
                description="Back-End Developer" />
                <x-teamcard 
                image="{{ asset('images/kyla-1x1.jpg') }}"
                name="Kyla De Asas" 
                description="Front-End Developer" />
                <x-teamcard 
                image="{{ asset('images/luke-1x1.jpg') }}"
                name="Luke Philip Dairocas" 
                description="Back-End Developer" />
                <x-teamcard 
                image="{{ asset('images/mark-1x1.jpg') }}"
                name="Mark Vincent Jungco" 
                description="Back-End Developer" />
            </div>
                <div class="max-w-md mx-auto mt-6">
                <x-teamcard 
                image="{{ asset('images/maluya-1x1.jpg') }}"
                name="Paul Warren Maluya" 
                description="Front-End Developer" />
                </div>
        </div>
    </body> 
    <x-footer />
</x-app-layout>
