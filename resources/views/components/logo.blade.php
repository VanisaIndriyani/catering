@props(['class' => 'logo-mark'])
@php($src = \App\Support\Logo::url())
@if ($src)
    <img src="{{ $src }}" alt="Logo Catering Calakan" {{ $attributes->merge(['class' => $class]) }}>
@else
    <span {{ $attributes->merge(['class' => 'logo-fallback '.$class]) }} aria-hidden="true">
        <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 30c0-10 6-16 14-16s14 6 14 16"/>
            <path d="M16 30h32c1 8-2 16-8 20H24c-6-4-9-12-8-20z"/>
            <path d="M32 14c2-4 6-6 8-6"/>
            <path d="M26 42c2 4 4 6 6 6s4-2 6-6"/>
        </svg>
    </span>
@endif
