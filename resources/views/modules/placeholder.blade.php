<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">{{ __($title) }}</h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-surface rounded-lg shadow-glass border border-hairline p-10 text-center">
            <div class="mx-auto h-14 w-14 rounded-full bg-accent/10 flex items-center justify-center">
                <svg class="h-7 w-7 text-accent" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-ink">{{ __($title) }}</h3>
            <p class="mt-2 text-sm text-ink-faint max-w-lg mx-auto">
                {{ __('This portal is under construction and will be delivered in the next build phase.') }}
            </p>
        </div>
    </div>
</x-app-layout>
