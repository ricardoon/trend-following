@props([
    'disabled' => false,
    'error' => $errors->get($attributes->whereStartsWith('wire:model')->first()),
    'size' => 'normal'
])

<div class="relative mt-2 rounded-md shadow-sm">
    <textarea
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->class([
            'block w-full rounded-md transition duration-200 ease-in-out',
            'text-sm' => $size == 'normal',
            'border-gray-300 focus:ring-indigo-700 focus:border-indigo-700' => !$error,
            'border-red-300 placeholder-red-300 focus:ring-red-500 focus:border-red-500' => $error])
        !!}
    >
        {{ $slot }}
    </textarea>
</div>

@error($attributes->whereStartsWith('wire:model')->first())
<p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
