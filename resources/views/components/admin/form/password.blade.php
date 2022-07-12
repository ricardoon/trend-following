@props([
    'disabled' => false,
    'hideError' => false,
    'error' => $errors->get($attributes->whereStartsWith('wire:model')->first()),
    'size' => 'normal'
])

<div x-data="{ show: false }" class="relative flex mt-2 rounded-md shadow-sm">
    {!! $left ?? '' !!}

    <input :type="show ? 'text' : 'password'" :placeholder="show ? '{{ __('Digite sua senha') }}' : '••••••••••'" autocomplete="current-password"
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->class([
            'block w-full rounded-md placeholder-gray-400 pl-4 pr-10 transition duration-200 ease-in-out',
            'py-3' => $size == 'large',
            'py-2 text-sm' => $size == 'normal',
            'border-gray-300 focus:ring-indigo-700 focus:border-indigo-700' => !$error,
            'border-red-300 placeholder-red-300 focus:ring-red-500 focus:border-red-500' => $error
        ]) !!}
    >

    <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" x-cloak>
        <i @click="show = true" x-show="!show" class="text-gray-400 far fa-fw fa-eye" data-tippy-content="{{ __('Mostrar senha') }}"></i>
        <i @click="show = false" x-show="show" class="text-gray-400 far fa-fw fa-eye-slash" data-tippy-content="{{ __('Ocultar senha') }}"></i>
    </div>
</div>

@if (!$hideError)
    @error($attributes->whereStartsWith('wire:model')->first())
    <p class="mt-2 text-red-600">{{ $message }}</p>
    @enderror
@endif
