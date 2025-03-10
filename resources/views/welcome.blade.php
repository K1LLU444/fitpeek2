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
                @auth
                <a
                    href="{{ url('/dashboard') }}"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:hover:text-white/80 dark:focus-visible:ring-white">
                    Home Page
                </a>
                @else
                <form action="{{ route('login') }}" method="GET">
                    <x-primary-button class=" sm:text-lg ml-10 shadow-xl">
                        SIGN IN
                    </x-primary-button>
                </form>

                @if (Route::has('register'))
                    <form action="{{ route('register') }}" method="GET">
                        <x-primary-button class="sm:text-lg ml-10 shadow-xl">
                            CREATE ACCOUNT
                        </x-primary-button>
                    </form>
                @endif
            @endauth
            </nav>
        </div>
    </div>
</body>
</html>
