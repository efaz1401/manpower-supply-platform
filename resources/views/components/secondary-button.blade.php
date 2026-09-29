<button {{ $attributes->merge(['type' => 'button', 'class' =>
    'inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl font-semibold text-xs uppercase tracking-widest
     bg-transparent text-ink-muted ring-1 ring-hairline
     hover:bg-surface-raised hover:text-ink active:scale-[0.98] disabled:opacity-40
     focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/70 focus-visible:ring-offset-2 focus-visible:ring-offset-canvas
     transition duration-150 ease-out']) }}>
    {{ $slot }}
</button>
