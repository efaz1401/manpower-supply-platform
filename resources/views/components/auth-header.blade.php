@props(['title', 'subtitle' => null])

{{-- Consistent heading for the auth screens. Sits above each form's fields. --}}
<div x-reveal class="mb-6">
    <h1 class="text-2xl font-bold tracking-tight text-ink">{{ $title }}</h1>
    @if ($subtitle)
        <p class="mt-1.5 text-sm text-ink-muted">{{ $subtitle }}</p>
    @endif
</div>
