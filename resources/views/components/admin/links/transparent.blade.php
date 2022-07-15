@props([])

<a {!! $attributes->class(['text-sm font-medium text-gray-500 hover:text-gray-700']) !!}>
    {{ $slot }}
</a>
