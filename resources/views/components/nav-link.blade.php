@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'inline-flex items-center px-3 py-2 text-sm font-semibold text-ink border-b-2 border-brand transition-colors'
                : 'inline-flex items-center px-3 py-2 text-sm font-semibold text-ink-muted border-b-2 border-transparent hover:text-ink hover:border-line-strong transition-colors';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
