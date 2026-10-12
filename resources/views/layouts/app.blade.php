<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>
            @yield('title', 'INELMY')
        </title>
        <link rel="icon" type="image/png" href="{{ asset('images/favicon_2024_2030.png') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')

    </head>

    <body>
       @include('layouts.navbar')
        <main>
            @yield('content')
        </main>
        @stack('scripts')
    </body>
</html>
