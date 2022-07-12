@props([
    'disabled' => false,
    'size' => 'normal'
])

<select {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->class([
        'block w-full pl-3 pr-10 mt-2 border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-700 focus:border-indigo-700',
        'py-2 text-sm' => $size == 'normal',
        'py-3' => $size == 'large'
    ]) !!}
>
    {{ $slot }}
</select>
