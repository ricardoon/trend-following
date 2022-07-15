@props([])

<a {!! $attributes->class(['text-sm font-medium text-indigo-600 hover:text-indigo-700']) !!}>
    {{ $slot }}
</a>
