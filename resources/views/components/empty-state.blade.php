{{--
    Shared empty state — "idle radar" motif.

    Replaces the old modules/placeholder.blade.php and the repeated
    `<td colspan="N">No records found.</td>` rows scattered across modules.

    Two shapes, chosen by whether `colspan` is set:
      <x-empty-state :colspan="6" />                     -> table row
      <x-empty-state title="…" message="…" />            -> panel (with optional slot for actions)

    Content is always visible (never gated behind JS) so it survives without Alpine.
--}}

@props([
    'colspan' => null,
    'title' => null,
    'message' => null,
    'icon' => 'compliance',
    'compact' => false,
])

@php
    $body = $message ?? __('No records found.');
    $size = $compact ? 'h-10 w-10' : 'h-14 w-14';
@endphp

@if ($colspan)
    {{-- Table-row shape (use inside @empty) --}}
    <tr>
        <td colspan="{{ $colspan }}" class="px-6 py-10 text-center">
            <div class="flex flex-col items-center gap-3">
                <span class="{{ $size }} relative inline-flex items-center justify-center text-ink-faint">
                    <svg viewBox="0 0 48 48" fill="none" class="h-full w-full" aria-hidden="true">
                        <circle cx="24" cy="24" r="18" stroke="currentColor" stroke-opacity="0.18" stroke-width="1" />
                        <circle cx="24" cy="24" r="11" stroke="currentColor" stroke-opacity="0.14" stroke-width="1" />
                        <circle cx="24" cy="24" r="4" stroke="currentColor" stroke-opacity="0.22" stroke-width="1" />
                        <line x1="24" y1="24" x2="38" y2="14" stroke="currentColor" stroke-opacity="0.35" stroke-width="1.25" stroke-linecap="round" />
                        <circle cx="24" cy="24" r="1.6" fill="currentColor" fill-opacity="0.5" />
                    </svg>
                </span>
                <p class="text-sm text-ink-faint">{{ $body }}</p>
                @if (trim($slot ?? '') !== '')
                    <div>{{ $slot }}</div>
                @endif
            </div>
        </td>
    </tr>
@else
    {{-- Panel shape --}}
    <div {{ $attributes->merge(['class' => 'glass-panel flex flex-col items-center px-6 py-12 text-center']) }}>
        <span class="{{ $size }} relative inline-flex items-center justify-center text-accent/70">
            <svg viewBox="0 0 48 48" fill="none" class="h-full w-full" aria-hidden="true">
                <circle cx="24" cy="24" r="18" stroke="currentColor" stroke-opacity="0.25" stroke-width="1" />
                <circle cx="24" cy="24" r="11" stroke="currentColor" stroke-opacity="0.2" stroke-width="1" />
                <circle cx="24" cy="24" r="4" stroke="currentColor" stroke-opacity="0.3" stroke-width="1" />
                <line x1="24" y1="24" x2="38" y2="14" stroke="currentColor" stroke-opacity="0.5" stroke-width="1.25" stroke-linecap="round" />
                <circle cx="24" cy="24" r="1.6" fill="currentColor" fill-opacity="0.6" />
            </svg>
        </span>

        @if ($title)
            <h3 class="mt-4 text-base font-semibold text-ink">{{ $title }}</h3>
        @endif

        <p class="mt-2 max-w-md text-sm leading-relaxed text-ink-faint">{{ $body }}</p>

        @if (trim($slot ?? '') !== '')
            <div class="mt-5">{{ $slot }}</div>
        @endif
    </div>
@endif
