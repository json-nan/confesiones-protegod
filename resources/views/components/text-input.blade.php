@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-3.5 py-2.5 text-sm text-ink bg-panel-muted border border-line rounded-lg placeholder:text-ink-faint focus:outline-none focus:ring-2 focus:ring-brand/50 focus:border-brand transition-colors']) }}>
