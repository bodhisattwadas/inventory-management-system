@props(['active' => false, 'disabled' => false])

@php
$classes = $disabled
            ? 'block w-full px-4 py-2 text-start text-sm leading-5 text-muted-foreground/60 cursor-not-allowed select-none opacity-60'
            : (($active ?? false)
            ? 'block w-full px-4 py-2 text-start text-sm leading-5 bg-accent text-accent-foreground focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full px-4 py-2 text-start text-sm leading-5 text-foreground hover:bg-muted focus:outline-none focus:bg-muted transition duration-150 ease-in-out');
@endphp

<a
    @if($disabled)
        aria-disabled="true"
        tabindex="-1"
        onclick="return false;"
    @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</a>
