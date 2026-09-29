{{--
    ASMC "Desert Signal" brand mark.
    An octagonal mashrabiya frame around a central signal node with radiating
    worker-nodes and connecting edges. Uses currentColor so it inherits the
    accent color (or a gradient via `text-transparent bg-clip-text` contexts).
    Optional `animate` attribute makes the outer nodes pulse gently.
--}}
@props(['animate' => false])

<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"
     {{ $attributes->merge(['class' => 'text-accent']) }}>
    {{-- Octagonal frame (mashrabiya) --}}
    <path d="M22 5h20l17 17v20L42 59H22L5 42V22L22 5Z"
          stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"
          opacity="0.35" fill="none" />

    {{-- Network edges from the core --}}
    <g stroke="currentColor" stroke-width="2" opacity="0.55">
        <line x1="32" y1="32" x2="32" y2="15" />
        <line x1="32" y1="32" x2="47" y2="24" />
        <line x1="32" y1="32" x2="47" y2="41" />
        <line x1="32" y1="32" x2="18" y2="41" />
        <line x1="32" y1="32" x2="18" y2="24" />
    </g>

    {{-- Outer worker-nodes --}}
    <g fill="currentColor" class="{{ $animate ? 'animate-pulse' : '' }}">
        <circle cx="32" cy="15" r="3" />
        <circle cx="47" cy="24" r="3" />
        <circle cx="47" cy="41" r="3" />
        <circle cx="18" cy="41" r="3" />
        <circle cx="18" cy="24" r="3" />
    </g>

    {{-- Core node --}}
    <circle cx="32" cy="32" r="5.5" fill="currentColor" />
    <circle cx="32" cy="32" r="9" stroke="currentColor" stroke-width="1.5" opacity="0.4" />
</svg>
