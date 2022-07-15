@props([
    'disabled' => false,
    'submit' => false,
    'color' => false,
])

<button
    {{ $disabled ? 'disabled' : '' }}
    {{ $submit ? 'type=submit' : 'type=button' }}
    {!! $attributes->class([
        'inline-flex items-center py-2 px-4 text-sm font-medium transition duration-75 border border-transparent',
        'text-gray-600' => !$color,
        'text-red-600' => $color == 'danger',
    ]) !!}
>
    {{ $slot }}
</button>
