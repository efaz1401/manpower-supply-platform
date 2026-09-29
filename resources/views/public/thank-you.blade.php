<x-public-layout :seo="$seo">

    <section class="mx-auto max-w-2xl px-4 py-24 text-center sm:px-6">
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-accent-soft text-accent">
            <x-icon name="check" class="h-7 w-7" />
        </span>
        <h1 class="mt-6 text-3xl font-bold">{{ __('marketing.thanks.h1') }}</h1>
        <p class="mt-4 leading-relaxed text-ink-muted">{{ __('marketing.thanks.body') }}</p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('public.home') }}" class="rounded-xl bg-accent px-6 py-3 font-semibold text-accent-contrast">{{ __('marketing.thanks.back') }}</a>
            @if ($phone = config('company.phone'))
                <a href="tel:{{ $phone }}" dir="ltr" class="rounded-xl border border-hairline px-6 py-3 font-semibold text-ink">{{ $phone }}</a>
            @endif
        </div>
    </section>

</x-public-layout>
