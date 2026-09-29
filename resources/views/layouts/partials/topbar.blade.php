@php($user = auth()->user())
<header class="h-16 glass border-x-0 border-t-0 flex items-center justify-between px-4 sm:px-6 relative z-20">
    <div class="flex items-center gap-3">
        {{-- Mobile sidebar toggle --}}
        <button type="button" x-data x-on:click="$store.sidebar.toggle()"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl ring-1 ring-hairline text-ink-muted hover:text-ink hover:bg-surface-raised transition-colors md:hidden"
                aria-label="{{ __('Menu') }}">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/>
            </svg>
        </button>

        <span class="hidden md:inline font-semibold text-ink">{{ __('ASMC Manpower') }}</span>

        {{-- ⌘K command palette launcher --}}
        <button type="button"
                x-data
                x-on:click="$dispatch('open-command-palette')"
                class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-surface-sunken ring-1 ring-hairline px-3 py-1.5 text-sm text-ink-faint hover:text-ink hover:ring-accent/40 transition-all">
            <x-icon name="search" class="h-4 w-4" />
            <span>{{ __('Search') }}…</span>
            <kbd class="ms-2 text-[10px] rounded bg-surface-raised px-1.5 py-0.5 ring-1 ring-hairline">⌘K</kbd>
        </button>
    </div>

    <div class="flex items-center gap-3">
        {{-- Hijri + Gregorian date chip --}}
        <span class="hidden lg:inline text-xs text-ink-faint tabular-nums">
            {{ \App\Support\DateFormatter::both(now()) }}
        </span>

        {{-- Theme toggle --}}
        <button type="button" x-data x-on:click="$store.theme.toggle()"
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl ring-1 ring-hairline text-ink-muted hover:text-ink hover:bg-surface-raised transition-colors"
                :title="$store.theme.current === 'dark' ? '{{ __('Light mode') }}' : '{{ __('Dark mode') }}'">
            <template x-if="$store.theme.current === 'dark'"><x-icon name="sun" class="h-5 w-5" /></template>
            <template x-if="$store.theme.current === 'light'"><x-icon name="moon" class="h-5 w-5" /></template>
        </button>

        {{-- Notifications bell --}}
        <button type="button" class="relative inline-flex h-9 w-9 items-center justify-center rounded-xl ring-1 ring-hairline text-ink-muted hover:text-ink hover:bg-surface-raised transition-colors">
            <x-icon name="bell" class="h-5 w-5" />
            <span class="absolute top-1.5 end-1.5 h-2 w-2 rounded-full bg-danger animate-pulse-ring"></span>
        </button>

        {{-- Language switcher (animated segmented) — desktop; the drawer carries it on mobile --}}
        <div class="hidden sm:flex items-center rounded-xl ring-1 ring-hairline overflow-hidden text-sm">
            @foreach (config('app.supported_locales') as $code => $meta)
                <a href="{{ route('locale.switch', $code) }}"
                   class="px-3 py-1.5 transition-colors {{ app()->getLocale() === $code ? 'bg-accent text-accent-contrast' : 'text-ink-muted hover:bg-surface-raised' }}">
                    {{ $meta['name'] }}
                </a>
            @endforeach
        </div>

        {{-- User dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 text-sm text-ink">
                <span class="hidden sm:inline font-medium">{{ $user?->displayName() }}</span>
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-accent/20 text-accent font-semibold ring-1 ring-accent/30">
                    {{ mb_substr($user?->displayName() ?? '?', 0, 1) }}
                </span>
            </button>
            <div x-show="open" @click.outside="open = false" x-transition x-cloak
                 class="absolute {{ app()->getLocale() === 'ar' ? 'start-0' : 'end-0' }} mt-2 w-48 glass-panel py-1 z-50">
                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-ink-muted hover:bg-surface-raised hover:text-ink">{{ __('Profile') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-start px-4 py-2 text-sm text-ink-muted hover:bg-surface-raised hover:text-ink">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
