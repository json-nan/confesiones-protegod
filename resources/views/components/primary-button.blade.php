<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center gap-2 px-4 py-2.5 bg-brand border border-transparent rounded-lg text-sm font-semibold text-white shadow-panel-sm hover:bg-brand-hover active:bg-brand-active focus-visible:ring-2 focus-visible:ring-brand/60 disabled:opacity-50 disabled:pointer-events-none transition-colors']) }}>
    {{ $slot }}
</button>
