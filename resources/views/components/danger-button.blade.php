<button {{ $attributes->merge(['type' => 'submit', 'class' =>
    'inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl font-semibold text-xs uppercase tracking-widest
     bg-danger text-accent-contrast
     hover:brightness-110 active:scale-[0.98]
     focus:outline-none focus-visible:ring-2 focus-visible:ring-danger/70 focus-visible:ring-offset-2 focus-visible:ring-offset-canvas
     transition duration-150 ease-out']) }}>
    {{ $slot }}
</button>
