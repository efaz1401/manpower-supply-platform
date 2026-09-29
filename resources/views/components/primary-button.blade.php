@props(['type' => 'submit'])

<button {{ $attributes->merge(['type' => $type, 'class' =>
    'inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl font-semibold text-xs uppercase tracking-widest
     bg-surface-raised text-ink ring-1 ring-hairline
     hover:bg-surface active:scale-[0.98]
     focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/70 focus-visible:ring-offset-2 focus-visible:ring-offset-canvas
     transition duration-150 ease-out']) }}>
    {{ $slot }}
</button>
