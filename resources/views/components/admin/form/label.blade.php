@props([
    'tippy',
    'max',
    'size' => 'normal'
])

<label {!! $attributes->class([
        'block font-medium text-gray-700',
        'text-sm' => $size == 'normal',
        'text-base' => $size == 'large'
    ]) !!} >
    {{ $slot }}
    @isset($tippy)
    <i class="fal fa-fw fa-question-circle cursor-help" data-tippy-content="{!! $tippy !!}"></i>
    @endisset
    @isset($max)
    <small class="float-right text-gray-400">{!! $max !!}</small>
    @endisset
</label>
