<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">{{ __('Documents') }}</h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 space-y-3 max-w-2xl">
        @forelse ($documents as $doc)
            <div class="bg-surface rounded-lg shadow-glass border border-hairline p-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-ink">{{ __(ucfirst(str_replace('_', ' ', $doc->type))) }}</p>
                    <p class="text-xs text-ink-faint">@dateboth($doc->expiry_date)</p>
                </div>
                <span class="px-2 py-1 rounded-full text-xs
                    {{ $doc->status === 'expired' ? 'bg-danger/15 text-danger' : ($doc->status === 'expiring' ? 'bg-warning/15 text-warning' : 'bg-success/15 text-success') }}">
                    {{ __(ucfirst($doc->status)) }}
                </span>
            </div>
        @empty
            <div class="bg-surface rounded-lg shadow-glass border border-hairline p-10 text-center text-ink-faint">—</div>
        @endforelse
    </div>
</x-app-layout>
