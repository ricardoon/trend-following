@props([
    'disabled' => false,
    'hideError' => false,
    'error' => $errors->get($attributes->whereStartsWith('wire:model')->first()),
    'size' => 'normal'
])

<div class="relative flex mt-2 rounded-md shadow-sm">
    {!! $left ?? '' !!}

    <input
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->class([
            'block px-4 w-full rounded-md placeholder-gray-400 transition duration-200 ease-in-out',
            'py-3' => $size == 'large',
            'py-2 text-sm' => $size == 'normal',
            'border-gray-300 focus:ring-indigo-700 focus:border-indigo-700' => !$error,
            'border-red-300 placeholder-red-300 focus:ring-red-500 focus:border-red-500' => $error,
            'bg-gray-50 text-gray-500' => $disabled
        ]) !!}
    >

    {!! $right ?? '' !!}
</div>

@if (!$hideError)
    @error($attributes->whereStartsWith('wire:model')->first())
    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
    @enderror
@endif
