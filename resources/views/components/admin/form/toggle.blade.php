@props([ 'size' => 'normal' ])

<div x-data="{ enabled: @entangle($attributes->whereStartsWith('wire:model')->first()).defer }" class="flex items-center">
    <button @click="enabled = !enabled" type="button" {!! $attributes !!} :class="{ 'bg-indigo-700': enabled, 'bg-gray-200': !enabled }" class="relative inline-flex h-6 transition duration-200 ease-in-out border-2 border-transparent rounded-full cursor-pointer shrink-0 w-11 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700" role="switch" aria-checked="false">
        <span aria-hidden="true" :class="{ 'translate-x-5': enabled, 'translate-x-0': !enabled }" class="inline-block w-5 h-5 transition duration-200 ease-in-out translate-x-0 bg-white rounded-full shadow pointer-events-none ring-0"></span>
    </button>

    <label class="ml-3 font-medium text-gray-900 cursor-pointer {{ $size == 'normal' ? 'text-sm' : '' }}">
        {{ $slot }}
    </label>
</div>
