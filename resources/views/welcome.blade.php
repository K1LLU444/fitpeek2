<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FitPeek</title>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="flex items-center py-2 font-semibold shadow-xl bg-white px-6 md:px-10 lg:px-16 fixed w-full top-0 z-50">
        <div class="flex items-center">
            <x-application-logo class="h-10 md:h-12 w-auto" />
            <a class="ml-3 text-lg md:text-xl font-bold">FitPeek</a>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex items-center justify-center md:justify-start text-center md:text-left px-6 md:px-10 lg:px-20 max-w-7xl mx-auto h-screen ml-[-10px]">
        <div class="w-full md:w-1/2 flex flex-col items-center md:items-start justify-center">
            <!-- Text Section -->
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-black leading-tight">
                Virtual Try-on
            </h1>
            <p class="text-lg md:text-2xl lg:text-3xl text-gray-600 mt-3">
                Create your style
            </p>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row items-center md:items-start mt-6 space-y-4 sm:space-y-0 sm:space-x-4">
                <form action="{{ route('login') }}" method="GET">
                    <x-primary-button class="w-40 sm:w-44 md:w-48 text-lg md:text-xl px-6 py-3 shadow-xl">
                        Log In
                    </x-primary-button>
                </form>

                @if (Route::has('register'))
                    <form action="{{ route('register') }}" method="GET">
                        <x-primary-button class="w-40 sm:w-30 md:w-48 text-lg md:text-xl px-6 py-3 shadow-xl">
                            Sign In
                        </x-primary-button>
                    </form>
                @endif
            </div>
        </div>
    </div>

</body>
</html>
