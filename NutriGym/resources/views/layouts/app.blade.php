<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width", initial-scale="1.0">
    <title>NutriGym</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('leaf.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="//unpkg.com/alpinejs" defer></script>

</head>
<body class="bg-gray-100">

    @include('layouts.navigation') <!-- aquí va la barra de navegación -->

    <main class="py-6 px-4">
        @yield('content')
    </main>

</body>
</html>
