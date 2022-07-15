@props([
    'round' => false,
])

<a {!! $attributes->class(['inline-flex items-center py-2 text-sm font-medium text-gray-600 transition duration-75 bg-white border border-transparent rounded-md shadow hover:text-gray-800 focus:outline-none hover:shadow-md', 'rounded-full py-2 px-2.5' => $round, 'px-4' => !$round]) !!}>
    {{ $slot }}
</a>
