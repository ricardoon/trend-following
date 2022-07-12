<!DOCTYPE html>
<html class="h-full bg-gray-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-admin.head.meta />

        @hasSection('head.title')
        <title>@yield('head.title') · {{ config('app.name') }}</title>
        @else
        <title>{{ config('app.name') }}</title>
        @endif

        {{-- Favicon --}}
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}">

        @livewireStyles
        <x-admin.head.fonts />
        <x-admin.head.styles />
        <x-admin.head.scripts />
    </head>
    <body class="h-full font-sans antialiased">
        <div class="min-h-screen bg-gray-50" x-data="{ openSidebar: false }" @keydown.window.escape="openSidebar = false">
            <x-admin.sidebar />

            <div class="flex flex-col flex-1 md:pl-64">
                <div class="sticky top-0 z-10 pt-1 pl-1 bg-white md:hidden sm:pl-3 sm:pt-3">
                    <button type="button" @click="openSidebar = !openSidebar" class="-ml-0.5 -mt-0.5 h-12 w-12 inline-flex items-center justify-center rounded-md text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <main class="flex-1">
                    <div class="py-6">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>

        <x-admin.notification />

        @livewireScripts
    </body>
</html>
