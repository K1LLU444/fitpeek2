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
<div class="flex flex-col md:flex-row items-center justify-center px-6 md:px-16 lg:px-24 max-w-7xl mx-auto pt-32 md:pt-40 gap-10">
    
    <!-- LEFT: Text Section -->
    <div class="w-full md:w-1/2 text-center md:text-left">
        <h1 class="text-4xl md:text-6xl font-bold text-black leading-tight">
            Virtual Try-on
        </h1>
        <p class="text-lg md:text-2xl text-gray-600 mt-4">
            Create your style
        </p>

        <!-- Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row justify-center md:justify-start gap-4">
            <form action="{{ route('login') }}" method="GET">
                <x-primary-button class="w-40 text-lg py-3 shadow-xl">
                    Log In
                </x-primary-button>
            </form>

            @if (Route::has('register'))
                <form action="{{ route('register') }}" method="GET">
                    <x-primary-button class="w-40 text-lg py-3 shadow-xl">
                        Sign Up
                    </x-primary-button>
                </form>
            @endif
        </div>
    </div>

    <!-- RIGHT: Image Section -->
    <div class="w-full md:w-1/2 flex justify-center">
        <img src="{{ asset('images/landing-page.png') }}" alt="Landing Page" class="w-full max-w-md h-auto">
    </div>

</div>





</body>
</html>
