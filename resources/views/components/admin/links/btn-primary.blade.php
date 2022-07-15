@props([
    'size' => 'normal'
])

<a
    {!! $attributes->class([
        'inline-flex items-center justify-center font-medium text-white transition duration-200 ease-in-out bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700',
        'px-6 py-3' => $size == 'large',
        'px-4 py-2 text-sm' => $size == 'normal'
    ]) !!}
>
    {{ $slot }}
</a>
