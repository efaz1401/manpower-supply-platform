<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">{{ __('Client Portal') }}</h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card :label="__('Active Deployments')" :value="$stats['active_deployments']" tone="emerald" />
            <x-stat-card :label="__('Pending Approvals')" :value="$stats['pending_timesheets']" tone="amber" />
            <x-stat-card :label="__('Outstanding')" :value="\App\Support\DateFormatter::money($stats['outstanding'])" tone="red" />
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('client.workers') }}" class="px-4 py-2 bg-accent text-accent-contrast rounded-md text-sm">{{ __('Workers') }}</a>
            <a href="{{ route('client.timesheets') }}" class="px-4 py-2 bg-surface-raised text-ink rounded-md text-sm">{{ __('Timesheets') }}</a>
            <a href="{{ route('client.invoices') }}" class="px-4 py-2 bg-surface-raised text-ink rounded-md text-sm">{{ __('Invoices') }}</a>
        </div>
    </div>
</x-app-layout>
