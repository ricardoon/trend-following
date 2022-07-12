@props([
    'disabled' => false,
    'hideError' => false,
    'error' => $errors->get($attributes->whereStartsWith('wire:model')->first()),
    'size' => 'normal'
])

<select {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->class([
        'block w-full pl-3 pr-10 mt-2 rounded-md shadow-sm focus:outline-none',
        'py-2 text-sm' => $size == 'normal',
        'py-3' => $size == 'large',
        'border-gray-300 focus:ring-indigo-700 focus:border-indigo-700' => !$error,
        'border-red-300 placeholder-red-300 focus:ring-red-500 focus:border-red-500' => $error,
    ]) !!}
>
    {{ $slot }}
</select>

@if (!$hideError)
    @error($attributes->whereStartsWith('wire:model')->first())
    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
    @enderror
@endif
