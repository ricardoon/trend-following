@props([
    'disabled' => false,
    'submit' => false,
    'size' => 'normal'
])

<button
    {{ $disabled ? 'disabled' : '' }}
    {{ $submit ? 'type=submit' : 'type=button'}}
    {!! $attributes->class([
        'inline-flex justify-center font-medium text-gray-800 transition duration-200 ease-in-out bg-neutral-300 border border-transparent rounded-md shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700',
        'px-6 py-3' => $size == 'large',
        'px-4 py-2 text-sm' => $size == 'normal'
    ]) !!}
    wire:loading.attr.delay="disabled"
    wire:loading.class.delay="bg-gray-200"
    wire:loading.class.delay.remove="bg-gray-400 hover:bg-gray-300"
>
    {{ $slot }}
</button>
