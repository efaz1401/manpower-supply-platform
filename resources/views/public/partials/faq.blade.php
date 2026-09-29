{{--
    Answers are rendered in the DOM at all times (only visually collapsed) so
    crawlers and AI answer engines read the full text without executing JS.
--}}
<section class="mx-auto max-w-3xl px-4 py-16 sm:px-6">
    <h2 class="text-2xl font-bold sm:text-3xl">{{ $heading ?? __('marketing.common.faq_title') }}</h2>

    <div class="mt-8 divide-y divide-hairline border-y border-hairline">
        @foreach ($faqs as $i => $faq)
            <details class="group py-4" @if ($i === 0) open @endif wire:key="faq-{{ $i }}">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-start font-semibold marker:content-none">
                    <span>{{ $faq['q'] }}</span>
                    <svg class="h-5 w-5 shrink-0 text-ink-faint transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                    </svg>
                </summary>
                <p class="mt-3 leading-relaxed text-ink-muted">{{ $faq['a'] }}</p>
            </details>
        @endforeach
    </div>
</section>
