@props([ 'max' ])

<p class="text-sm text-gray-400">
    <span x-text="counter ? counter.length : 0">0</span>/{{ $max }} caracteres
</p>