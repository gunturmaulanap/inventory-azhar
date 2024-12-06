<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Cetak Nota')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('vendor/izitoast/dist/css/iziToast.min.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        @media print {
            /* Hide URL and other unnecessary parts in the header/footer */


            .printable-content {
                visibility: visible;
            }

            header,
            footer {
                display: none;
                /* Hide header and footer during print */
            }

            /* You can also hide page numbers and URL from the browser print settings */
            @page {
                margin: 0;
            }

            /* If the URL is appearing on the print layout, you can hide it like this */
            .no-print {
                display: none;
            }
        }
    </style>


</head>

<body class="font-sans antialiased bg-gray-50 flex items-center justify-center">
    {{ $slot }}
    @livewireScripts
</body>

</html>
