@props([
    'disabled' => false,
    'submit' => false,
    'size' => 'normal'
])

<button
    {{ $disabled ? 'disabled' : '' }}
    {{ $submit ? 'type=submit' : 'type=button'}}
    {!! $attributes->class([
        'inline-flex justify-center font-medium text-white transition duration-200 ease-in-out bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700',
        'px-6 py-3' => $size == 'large',
        'px-4 py-2 text-sm' => $size == 'normal'
    ]) !!}
    wire:loading.attr.delay="disabled"
    wire:loading.class.delay="bg-gray-200"
    wire:loading.class.delay.remove="bg-indigo-600 hover:bg-indigo-700"
    wire:target="{{ $attributes->whereStartsWith('wire:target')->first() ?? $attributes->whereStartsWith('wire:click')->first() ?? 'save' }}"
>
    <span class="mr-2 text-gray-800" wire:loading.delay wire:target="{{ $attributes->whereStartsWith('wire:target')->first() ?? $attributes->whereStartsWith('wire:click')->first() ?? 'save' }}">
        <i class="far fa-fw fa-spinner fa-spin"></i>
    </span>
    {{ $slot }}
</button>
