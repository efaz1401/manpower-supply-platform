@php
    $phone = config('company.phone');
    $whatsapp = config('company.whatsapp');
@endphp

<section class="mx-auto max-w-7xl px-4 py-16 sm:px-6">
    <div class="glass-panel relative overflow-hidden p-8 text-center sm:p-12">
        <div class="aurora" aria-hidden="true"></div>
        <div class="relative">
            <h2 class="text-2xl font-bold sm:text-3xl">{{ $title }}</h2>
            <p class="mx-auto mt-3 max-w-2xl text-ink-muted">{{ $body }}</p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('public.contact') }}" class="rounded-xl bg-accent px-6 py-3 font-semibold text-accent-contrast shadow-glow-accent">{{ __('marketing.common.cta_primary') }}</a>
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}" rel="nofollow noopener" target="_blank" class="rounded-xl border border-hairline px-6 py-3 font-semibold text-ink transition-colors hover:border-accent hover:text-accent">{{ __('marketing.common.cta_whatsapp') }}</a>
                @endif
                @if ($phone)
                    <a href="tel:{{ $phone }}" dir="ltr" class="rounded-xl border border-hairline px-6 py-3 font-semibold text-ink transition-colors hover:border-accent hover:text-accent">{{ $phone }}</a>
                @endif
            </div>

            <p class="mt-5 text-xs text-ink-faint">{{ __('marketing.common.response_time') }} · {{ __('marketing.common.coverage') }}</p>
        </div>
    </div>
</section>
