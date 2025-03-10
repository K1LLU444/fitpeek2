<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FitPeek') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div>
            <!-- Remove or comment out this logo to avoid duplicate logos -->
            {{-- <div>
                <a href="/">
                    <x-application-logo class="h-20 fill-current text-gray-500" />
                </a>
            </div> --}}

            <div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
