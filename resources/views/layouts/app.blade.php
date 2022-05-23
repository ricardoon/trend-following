<!DOCTYPE html>
<html class="h-full bg-gray-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @hasSection('title')
        <title>@yield('title') · {{ config('app.name') }}</title>
        @else
        <title>{{ config('app.name') }}</title>
        @endif

        {{-- Favicon --}}
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

        @livewireStyles
    </head>
    <body class="h-full font-sans antialiased">
        <div class="min-h-screen bg-white" x-data="{ open: false }" @keydown.window.escape="open = false">
            @include('layouts.navigation')

            <div class="md:pl-64 flex flex-col flex-1">
                <div class="sticky top-0 z-10 md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 bg-white">
                    <button type="button" @click="open = !open" class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <main class="flex-1">
                    <div class="py-6">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                        </div>
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            <!-- Replace with your content -->
                            <div class="py-4">
                                <div class="border-4 border-dashed border-gray-200 rounded-lg h-96"></div>
                            </div>
                            <!-- /End replace -->
                        </div>
                    </div>
                </main>
            </div>
        </div>
        @livewireScripts
    </body>
</html>
