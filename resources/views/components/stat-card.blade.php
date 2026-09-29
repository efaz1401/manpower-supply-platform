@props(['label' => '', 'value' => '', 'sub' => null, 'tone' => 'default', 'icon' => null])

@php
    // Accent color per tone (drives the numeral + edge-glow).
    $tones = [
        'default' => 'text-ink',
        'emerald' => 'text-accent',
        'amber'   => 'text-warning',
        'red'     => 'text-danger',
        'blue'    => 'text-info',
    ];
    $glow = [
        'default' => 'before:bg-ink-faint/20',
        'emerald' => 'before:bg-accent/60',
        'amber'   => 'before:bg-warning/60',
        'red'     => 'before:bg-danger/60',
        'blue'    => 'before:bg-info/60',
    ];
    $valueClass = $tones[$tone] ?? $tones['default'];
    $glowClass = $glow[$tone] ?? $glow['default'];

    // Only animate a count-up when the value is purely numeric.
    $numeric = is_numeric($value);
@endphp

<div x-data
     {{ $attributes->merge(['class' =>
        "glass-panel relative overflow-hidden p-5 group transition-transform duration-300 hover:-translate-y-0.5
         before:absolute before:inset-x-0 before:top-0 before:h-px before:content-[''] $glowClass"]) }}>
    <div class="flex items-start justify-between gap-3">
        <p class="text-sm text-ink-muted">{{ $label }}</p>
        @if ($icon)
            <span class="text-ink-faint group-hover:text-accent transition-colors">
                <x-icon :name="$icon" class="h-5 w-5" />
            </span>
        @endif
    </div>

    <p class="mt-3 text-3xl font-bold tabular-nums {{ $valueClass }}">
        @if ($numeric)
            {{-- Real value is the fallback so the card never renders a misleading
                 0 when JS is unavailable; x-countup overwrites it on reveal. --}}
            <span x-countup="{{ $value }}">{{ $value }}</span>
        @else
            {{ $value }}
        @endif
    </p>

    @if ($sub)
        <p class="mt-1 text-xs text-ink-faint">{{ $sub }}</p>
    @endif
</div>
