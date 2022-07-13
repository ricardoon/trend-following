@props([])

<a {!! $attributes->class(['block px-4 py-4 text-sm font-medium text-center text-gray-500 bg-gray-50 hover:text-gray-700 sm:rounded-b-lg']) !!}>
    {{ $slot }}
</a>
