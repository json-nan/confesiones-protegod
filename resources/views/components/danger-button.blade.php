<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center gap-2 px-4 py-2.5 bg-error border border-transparent rounded-lg text-sm font-semibold text-white shadow-panel-sm hover:bg-red-500 active:bg-red-700 focus-visible:ring-2 focus-visible:ring-error/60 disabled:opacity-50 disabled:pointer-events-none transition-colors']) }}>
    {{ $slot }}
</button>
