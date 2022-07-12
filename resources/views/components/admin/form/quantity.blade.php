@props([
    'disabled' => false,
    'hideError' => false,
    'error' => $errors->get($attributes->whereStartsWith('wire:model')->first()),
    'size' => 'normal',
    'add' => $add,
    'remove' => $remove
])

<div class="relative flex mt-2 rounded-md shadow-sm">
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 cursor-pointer" wire:click="{{ $remove }}">
        <i class="text-gray-400 hover:text-indigo-700 fal fa-fw fa-minus"></i>
    </div>

    <input
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->class([
            'block px-10 w-full rounded-md placeholder-gray-400 transition duration-200 ease-in-out text-center',
            'py-3' => $size == 'large',
            'py-2 text-sm' => $size == 'normal',
            'border-gray-300 focus:ring-indigo-700 focus:border-indigo-700' => !$error,
            'border-red-300 placeholder-red-300 focus:ring-red-500 focus:border-red-500' => $error,
            'bg-gray-50 text-gray-500' => $disabled
        ]) !!}
    >

    <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" wire:click="{{ $add }}">
        <i class="text-gray-400 hover:text-indigo-700 fal fa-fw fa-plus"></i>
    </div>
</div>

@if (!$hideError)
    @error($attributes->whereStartsWith('wire:model')->first())
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
@endif
