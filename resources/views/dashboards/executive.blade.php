<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">{{ __('Executive Dashboard') }}</h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card :label="__('Total Workers')" :value="$stats['total_workers']" icon="workers" />
            <x-stat-card :label="__('Deployed')" :value="$stats['deployed']" tone="emerald" icon="deployments" />
            <x-stat-card :label="__('Available')" :value="$stats['available']" tone="blue" icon="workers" />
            <x-stat-card :label="__('Utilization')" :value="$stats['utilization'] . '%'" tone="emerald" icon="executive" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card :label="__('Active Clients')" :value="$stats['active_clients']" icon="clients" />
            <x-stat-card :label="__('Active Deployments')" :value="$stats['active_deployments']" icon="deployments" />
            <x-stat-card :label="__('Upcoming Expiries')" :value="$stats['expiring_docs']"
                         :tone="$stats['expiring_docs'] > 0 ? 'amber' : 'default'" icon="compliance" />
            <x-stat-card :label="__('Outstanding')" :value="\App\Support\DateFormatter::money($stats['outstanding'])" tone="red" icon="invoices" />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="glass-panel p-6">
                <h3 class="font-semibold text-ink">{{ __('Nitaqat Band') }}</h3>
                <p class="mt-3 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-success/15 text-success ring-1 ring-success/25 text-sm font-medium">
                    <span class="h-1.5 w-1.5 rounded-full bg-success"></span>
                    {{ __('Green') ?? 'Green' }}
                </p>
                <p class="mt-3 text-xs text-ink-faint">{{ __('Manually maintained by management.') }}</p>
            </div>
            <div class="glass-panel p-6">
                <h3 class="font-semibold text-ink">{{ __('Compliance Health') }}</h3>
                <p class="mt-3 text-3xl font-bold {{ $stats['expiring_docs'] > 0 ? 'text-warning' : 'text-accent' }}">
                    {{ $stats['expiring_docs'] > 0 ? __('Attention') : __('Healthy') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
