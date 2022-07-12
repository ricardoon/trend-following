@props([
    'disabled' => false,
    'submit' => false,
    'size' => 'normal'
])

<button 
    {{ $disabled ? 'disabled' : '' }}
    {{ $submit ? 'type=submit' : 'type=button'}}
    {!! $attributes->class([
        'inline-flex items-center justify-center font-medium text-red-700 transition duration-75 bg-red-100 border border-transparent rounded-md shadow-sm hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400',
        'px-6 py-3' => $size == 'large',
        'px-4 py-2 text-sm' => $size == 'normal'
    ]) !!}
    wire:loading.attr.delay="disabled" 
    wire:loading.class.delay="bg-gray-100 text-gray-700" 
    wire:loading.class.delay.remove="bg-red-100 hover:bg-red-200 text-red-700"
    wire:target="{{ $attributes->whereStartsWith('wire:target')->first() ?? $attributes->whereStartsWith('wire:click')->first() ?? 'save' }}"
>
    <span class="mr-2 text-gray-800" wire:loading.delay wire:target="{{ $attributes->whereStartsWith('wire:click')->first() ?? 'save' }}">
        <i class="far fa-fw fa-spinner fa-spin"></i>
    </span>
    {{ $slot }}
</button>
