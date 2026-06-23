<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center gap-2 px-4 py-2.5 bg-transparent border border-line-strong rounded-lg text-sm font-semibold text-ink hover:bg-panel focus-visible:ring-2 focus-visible:ring-brand/60 disabled:opacity-50 disabled:pointer-events-none transition-colors']) }}>
    {{ $slot }}
</button>
