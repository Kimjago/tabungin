@props(['variant' => 'neutral'])

@php
    $classes = match ($variant) {
        'income' => 'badge-income',
        'expense' => 'badge-expense',
        default => 'badge-neutral',
    };
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if ($variant === 'income')
        <svg class="size-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 9V3M3 6l3-3 3 3" />
        </svg>
    @elseif ($variant === 'expense')
        <svg class="size-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 3v6M3 6l3 3 3-3" />
        </svg>
    @endif
    {{ $slot }}
</span>