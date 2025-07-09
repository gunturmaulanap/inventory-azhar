<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div id="page" class="min-h-screen flex items-center justify-center bg-cover bg-no-repeat bg-center relative"
        style="background-image: url('{{ asset('img/logo-azhar.png') }}')">

        <!-- Overlay Putih Transparan (optional) -->
        <div class="absolute inset-0 bg-white opacity-70"></div>

        <!-- Kontainer Login -->
        <div class="relative w-full max-w-md px-6 py-8 bg-white bg-opacity-90 rounded-lg z-10 shadow-xl">
            <h2 class="text-xl font-semibold text-center mb-6">Masuk Akun</h2>
            {{ $slot }}
        </div>
    </div>
</body>

</html>
