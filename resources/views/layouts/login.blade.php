<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>
            @yield('title', 'INELMY')
        </title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')

    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <main class="col-md-9 col-lg-10 p-4">
                    @yield('content')
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
