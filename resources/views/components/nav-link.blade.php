@props(['active' => false, 'icon' => 'home'])

@php
    $icons = [
        'home' => 'M3 10.5 12 3l9 7.5M5.25 9.75V20.25A.75.75 0 0 0 6 21h4.5v-5.25h3V21H18a.75.75 0 0 0 .75-.75V9.75',
        'list' => 'M8 6h13M8 12h13M8 18h13M3.5 6h.01M3.5 12h.01M3.5 18h.01',
        'wallet' => 'M3 7.5A2.25 2.25 0 0 1 5.25 5.25h13.5A2.25 2.25 0 0 1 21 7.5v9A2.25 2.25 0 0 1 18.75 18.75H5.25A2.25 2.25 0 0 1 3 16.5v-9Zm13.5 3.75h.008v.008H16.5V11.25Z',
        'target' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-4.5a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm0-3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z',
        'chart' => 'M3.75 20.25h16.5M7.5 16.5V9.75M12 16.5V4.5M16.5 16.5v-4.5',
        'tag' => 'M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a2.25 2.25 0 0 0 3.182 0l4.318-4.318a2.25 2.25 0 0 0 0-3.182L11.16 3.659A2.25 2.25 0 0 0 9.568 3Z',
        'logout' => 'M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H3',
    ];
@endphp

<a {{ $attributes->merge([
    'class' => 'nav-link' . ($active ? ' nav-link-active' : ''),
    'aria-current' => $active ? 'page' : null,
]) }}>
    <svg class="size-[18px] shrink-0" fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
         aria-hidden="true">
        <path d="{{ $icons[$icon] ?? $icons['home'] }}" />
    </svg>
    <span>{{ $slot }}</span>
</a>