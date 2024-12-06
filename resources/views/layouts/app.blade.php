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

    <link rel="stylesheet" href="{{ asset('vendor/izitoast/dist/css/iziToast.min.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen">
        @include('layouts.navigation')

        <div class="flex overflow-y-auto">
            {{-- SIDEBAR --}}
            @if (in_array(auth()->user()->role, ['super_admin', 'admin']))
                @include('layouts.sidebar')
            @endif
            <div class="w-full">
                {{-- HEADER --}}
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto flex items-center py-6 px-4 px-8 divide-x">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight pr-4">
                            {{ $title ?? '' }}
                        </h2>
                        <nav aria-label="Breadcrumb" class="ps-6">
                            <ol class="flex items-center gap-1 text-sm text-gray-600">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="block transition hover:text-gray-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 mb-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                    </a>
                                </li>
                                {{ $breadcrumb ?? '' }}
                            </ol>
                        </nav>
                    </div>
                </header>

                {{-- MAIN CONTENT --}}
                <main class="overflow-y-auto h-[78vh]">
                    <div class="max-w-7xl mx-auto px-8 py-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        @include('layouts.js.alert')
        @stack('scripts')
        @livewireScripts
</body>

</html>
