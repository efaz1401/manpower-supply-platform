@php
    $locale = app()->getLocale();
    $phone = config('company.phone');
    $phoneAlt = config('company.phone_alt');
    $email = config('company.email');
    $cr = config('company.cr_number');
    $addressLine = collect([
        config("company.address.street.$locale"),
        config("company.address.governorate.$locale"),
        config("company.address.region.$locale"),
        $locale === 'ar' ? 'المملكة العربية السعودية' : 'Saudi Arabia',
    ])->filter()->implode('، ');
    $footerServices = collect(config('company.services'))->filter(fn ($m) => $m['featured'])->keys();
    $footerCities = collect(config('company.cities'))->filter(fn ($c) => $c['tier'] <= 2);
@endphp

<footer class="border-t border-hairline bg-surface-sunken">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6">
        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <x-application-logo class="h-8 w-8 text-accent" />
                    <span class="font-bold">{{ config("company.short_name.$locale") }}</span>
                </div>
                <p class="mt-4 text-sm leading-relaxed text-ink-muted">{{ __('marketing.footer.tagline') }}</p>
                <address class="mt-4 not-italic text-sm leading-relaxed text-ink-faint">{{ $addressLine }}</address>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-ink">{{ __('marketing.footer.services') }}</h2>
                <ul class="mt-4 space-y-2 text-sm">
                    @foreach ($footerServices as $slug)
                        <li><a href="{{ route('public.service', ['service' => $slug]) }}" class="text-ink-muted transition-colors hover:text-accent">{{ __("marketing.services.$slug.name") }}</a></li>
                    @endforeach
                    <li><a href="{{ route('public.services') }}" class="font-medium text-accent">{{ __('marketing.common.all_services') }}</a></li>
                </ul>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-ink">{{ __('marketing.footer.locations') }}</h2>
                <ul class="mt-4 space-y-2 text-sm">
                    @foreach ($footerCities as $slug => $city)
                        <li><a href="{{ route('public.city', ['city' => $slug]) }}" class="text-ink-muted transition-colors hover:text-accent">{{ $city[$locale] ?? $city['en'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h2 class="text-sm font-semibold text-ink">{{ __('marketing.footer.contact') }}</h2>
                <ul class="mt-4 space-y-2 text-sm">
                    @if ($phone)
                        <li><a href="tel:{{ $phone }}" dir="ltr" class="text-ink-muted transition-colors hover:text-accent">{{ $phone }}</a></li>
                    @endif
                    @if ($phoneAlt)
                        <li><a href="tel:{{ $phoneAlt }}" dir="ltr" class="text-ink-muted transition-colors hover:text-accent">{{ $phoneAlt }}</a></li>
                    @endif
                    @if ($email)
                        <li><a href="mailto:{{ $email }}" dir="ltr" class="text-ink-muted transition-colors hover:text-accent">{{ $email }}</a></li>
                    @endif
                    <li><a href="{{ route('public.contact') }}" class="font-medium text-accent">{{ __('marketing.common.cta_primary') }}</a></li>
                    <li class="pt-2"><a href="{{ route('login') }}" class="text-ink-faint transition-colors hover:text-ink">{{ __('marketing.nav.login') }}</a></li>
                </ul>
                <p class="mt-4 text-xs text-ink-faint">{{ __('marketing.contact.hours') }}</p>
            </div>
        </div>

        <div class="mt-10 flex flex-col gap-3 border-t border-hairline pt-6 text-xs text-ink-faint sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ config("company.legal_name.$locale") }}. {{ __('marketing.footer.rights') }}</p>
            <p class="flex flex-wrap items-center gap-x-4 gap-y-1">
                @if ($cr)
                    <span>{{ __('marketing.footer.cr') }}: <span dir="ltr">{{ $cr }}</span></span>
                @endif
                <span>{{ __('marketing.footer.disclaimer') }}</span>
            </p>
        </div>
    </div>
</footer>
