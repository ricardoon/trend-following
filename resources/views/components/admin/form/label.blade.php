@props([
    'tippy',
    'size' => 'normal'
])

<label {!! $attributes->class(['block font-medium text-gray-700', 'text-sm' => $size == 'normal', 'text-base' => $size == 'large']) !!}>
    {{ $slot }}
    @isset($tippy)
    <i class="fal fa-fw fa-question-circle cursor-help" data-tippy-content="{{ $tippy }}"></i>
    @endisset
</label>
