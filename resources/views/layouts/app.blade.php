<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'INELMY')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    @include('layouts.partials.navbar')

    <div class="container-fluid">
        <div class="row">

            <aside class="col-md-3 col-lg-2 bg-light min-vh-100 p-3">
                @include('layouts.partials.sidebar')
            </aside>

            <main class="col-md-9 col-lg-10 p-4">
                @yield('content')
            </main>

        </div>
    </div>

</body>

</html>
