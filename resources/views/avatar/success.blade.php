<x-app-layout>
    <body class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
        <div class="mx-auto mt-20 mb-20 bg-white p-6 sm:p-8 rounded-lg shadow-2xl w-full max-w-md">
            <h2 class="text-xl sm:text-2xl font-bold text-center mb-4">Avatar Created Successfully!</h2>
            <p class="text-center">Your measurements have been saved.</p>

            <!-- Buttons Section -->
            <div class="flex justify-center mt-6 space-x-4">
                <!-- Back to Dashboard Button -->
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-green-600 text-black rounded-lg shadow hover:bg-green-700 transition">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </body>
</x-app-layout>
