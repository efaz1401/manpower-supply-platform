@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' =>
    'rounded-xl bg-surface-sunken text-ink placeholder:text-ink-faint
     border-hairline shadow-sm
     focus:border-accent focus:ring-2 focus:ring-accent/40
     disabled:opacity-50 disabled:cursor-not-allowed
     transition duration-150']) }}>
