@props([ 'disabled' => false ])

<input type="checkbox" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-4 h-4 mr-2 text-amber-500 border-gray-300 rounded focus:ring-amber-500']) !!}>
