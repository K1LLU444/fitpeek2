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
<body>
    <header class="flex items-center py-2 font-semibold shadow-xl bg-white px-6 md:px-10 lg:px-16">
        <div class="flex justify-start">
            <x-application-logo class="h-16 w-auto" />
            <a class="flex justify-center items-center ml-5 font-vold text-2xl">FitPeek</a>
        </div>
    </header>

    <div class="w-30 flex flex-col items-start">
        <div class="mb-10 ml-20 m-40">
            <p class="rem text-8xl font-bold mb-5 text-primary-color">Virtual Try-on</p>
            <p class="rem text-5xl ml-3 text-secondary-color">Create your style</p>
        </div>

        <div class="flex space-x-4 ml-20">
            <nav class="-mx-3 flex flex-1 justify-end">

                <form action="{{ route('login') }}" method="GET">
                    <x-primary-button class="w-3/4 sm:text-lg ml-10 shadow-xl">
                        Log In
                    </x-primary-button>
                </form>

                @if (Route::has('register'))
                    <form action="{{ route('register') }}" method="GET">
                        <x-primary-button class="w-3/4 sm:text-lg ml-10 shadow-xl">
                            Sign In
                        </x-primary-button>
                    </form>
                @endif
            </nav>
        </div>
    </div>
</body>
</html>
