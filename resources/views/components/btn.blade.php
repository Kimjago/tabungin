@props(['variant' => 'primary', 'size' => 'md', 'href' => null, 'type' => 'submit'])

@php
    $classes = match ($variant) {
        'secondary' => 'btn-secondary',
        'ghost' => 'btn-ghost',
        'danger' => 'btn-danger',
        default => 'btn-primary',
    };

    if ($size === 'sm') {
        $classes .= ' btn-sm';
    }
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif