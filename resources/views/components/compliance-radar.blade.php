@props(['blips' => [], 'counts' => ['critical' => 0, 'warning' => 0, 'info' => 0]])

@php
    // Ring labels: inner = most urgent. Mirrors the 90-day EXPIRING_WINDOW_DAYS scale.
    $rings = [90 => '90d', 68 => '60d', 46 => '30d', 24 => '0d'];
    $tone = [
        'critical' => ['fill' => 'rgb(var(--ds-danger))', 'glow' => 'rgb(var(--ds-danger) / 0.35)'],
        'warning'  => ['fill' => 'rgb(var(--ds-warning))', 'glow' => 'rgb(var(--ds-warning) / 0.35)'],
        'info'     => ['fill' => 'rgb(var(--ds-info))', 'glow' => 'rgb(var(--ds-info) / 0.35)'],
    ];
    $total = ($counts['critical'] ?? 0) + ($counts['warning'] ?? 0) + ($counts['info'] ?? 0);
@endphp

<div {{ $attributes->merge(['class' => 'glass-panel relative overflow-hidden p-6']) }}>
    <div class="grid gap-8 lg:grid-cols-[auto,1fr] lg:items-center">

        {{-- ---------------------------------------------------------------- --}}
        {{-- Radar scope                                                      --}}
        {{-- ---------------------------------------------------------------- --}}
        <div class="relative mx-auto h-[280px] w-[280px] shrink-0">
            {{-- Rotating sweep. Pure CSS; frozen by the reduced-motion kill-switch. --}}
            <div class="ds-radar-sweep absolute inset-0 rounded-full" aria-hidden="true"></div>

            <svg viewBox="0 0 200 200" class="relative h-full w-full" role="img"
                 aria-label="{{ __('Compliance radar') }}">
                {{-- Concentric range rings --}}
                @foreach ($rings as $r => $label)
                    <circle cx="100" cy="100" r="{{ $r }}"
                            fill="none" stroke="rgb(var(--ds-hairline))" stroke-width="1"
                            opacity="{{ $loop->first ? 0.9 : 0.55 }}" />
                @endforeach

                {{-- Cross-hairs --}}
                <line x1="10" y1="100" x2="190" y2="100" stroke="rgb(var(--ds-hairline))" stroke-width="1" opacity="0.4" />
                <line x1="100" y1="10" x2="100" y2="190" stroke="rgb(var(--ds-hairline))" stroke-width="1" opacity="0.4" />

                {{-- Ring scale labels (inner ring = expiry day zero) --}}
                @foreach ($rings as $r => $label)
                    <text x="102" y="{{ 100 - $r + 9 }}" font-size="7"
                          fill="rgb(var(--ds-ink-faint))">{{ $label }}</text>
                @endforeach

                {{-- Blips --}}
                @foreach ($blips as $b)
                    @php $t = $tone[$b['severity']] ?? $tone['info']; @endphp
                    <g wire:key="blip-{{ $b['key'] }}">
                        <title>{{ $b['title'] }} — {{ __(':count days left', ['count' => max(0, $b['days'])]) }}</title>
                        {{-- Soft halo --}}
                        <circle cx="{{ $b['x'] }}" cy="{{ $b['y'] }}" r="6" fill="{{ $t['glow'] }}"
                                @class(['ds-blip-pulse' => $b['severity'] === 'critical']) />
                        {{-- Core --}}
                        <circle cx="{{ $b['x'] }}" cy="{{ $b['y'] }}" r="2.6" fill="{{ $t['fill'] }}" />
                    </g>
                @endforeach

                {{-- Centre marker --}}
                <circle cx="100" cy="100" r="2" fill="rgb(var(--ds-gold))" opacity="0.8" />
            </svg>

            {{-- Idle state: no active alerts --}}
            @if ($total === 0)
                <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                    <span class="rounded-full glass px-3 py-1 text-xs text-ink-muted">{{ __('All clear') }}</span>
                </div>
            @endif
        </div>

        {{-- ---------------------------------------------------------------- --}}
        {{-- Legend / counts                                                  --}}
        {{-- ---------------------------------------------------------------- --}}
        <div class="space-y-4">
            <div>
                <p class="text-sm text-ink-muted">{{ __('Active alerts') }}</p>
                <p class="mt-1 text-4xl font-bold tabular-nums text-ink">
                    <span x-data x-countup="{{ $total }}">0</span>
                </p>
                <p class="mt-1 text-xs text-ink-faint">
                    {{ __('Plotted by time to expiry — inner ring is most urgent.') }}
                </p>
            </div>

            <dl class="grid grid-cols-3 gap-3">
                @foreach ([['critical', __('Critical'), 'bg-danger'], ['warning', __('Warning'), 'bg-warning'], ['info', __('Info'), 'bg-info']] as [$key, $label, $dot])
                    <div class="rounded-xl border border-hairline bg-surface-sunken/40 p-3">
                        <dt class="flex items-center gap-1.5 text-xs text-ink-muted">
                            <span class="h-1.5 w-1.5 rounded-full {{ $dot }}"></span>{{ $label }}
                        </dt>
                        <dd class="mt-1 text-xl font-semibold tabular-nums text-ink">{{ $counts[$key] ?? 0 }}</dd>
                    </div>
                @endforeach
            </dl>

            {{ $slot }}
        </div>
    </div>
</div>
