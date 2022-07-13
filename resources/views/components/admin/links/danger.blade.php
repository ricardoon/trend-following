@props([
    'size' => 'normal'
])

<a
    {!! $attributes->class([
        'inline-flex items-center justify-center font-medium text-red-700 transition duration-75 bg-red-100 border border-transparent rounded-md shadow-sm hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400',
        'px-6 py-3' => $size == 'large',
        'px-4 py-2 text-sm' => $size == 'normal'
    ]) !!}
>
    {{ $slot }}
</a>
