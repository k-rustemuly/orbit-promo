@props([
    'color' => null,
    'value' => ''
])
<span {{ $attributes->merge(['class' => 'badge', 'style' => 'background-color:'.($color ?? '#ffffff')]) }}>
    {{ $value }}
</span>
