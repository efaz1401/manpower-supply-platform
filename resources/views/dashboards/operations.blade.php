<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">{{ __('Operations Dashboard') }}</h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <x-stat-card :label="__('Available')" :value="$stats['available']" tone="blue" icon="workers" />
            <x-stat-card :label="__('Pending Approvals')" :value="$stats['pending_timesheets']" tone="amber" icon="timesheets" />
            <x-stat-card :label="__('Deployments')" :value="$stats['pending_deployments']" icon="deployments" />
            <x-stat-card :label="__('Expiring')" :value="$stats['expiring_docs']" tone="amber" icon="compliance" />
            <x-stat-card :label="__('Expired')" :value="$stats['expired_docs']" tone="red" icon="compliance" />
        </div>

        <div class="glass-panel">
            <div class="px-6 py-4 border-b border-hairline">
                <h3 class="font-semibold text-ink">{{ __('Upcoming Expiries') }}</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-surface-sunken/60 text-ink-faint">
                        <tr>
                            <th class="px-6 py-3 text-start font-medium">{{ __('Documents') }}</th>
                            <th class="px-6 py-3 text-start font-medium">{{ __('Expiry Date') }}</th>
                            <th class="px-6 py-3 text-start font-medium">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline text-ink-muted">
                        @forelse ($expiring as $doc)
                            <tr class="hover:bg-surface-raised/50 transition-colors">
                                <td class="px-6 py-3 text-ink">{{ __(ucfirst(str_replace('_', ' ', $doc->type))) }} — {{ $doc->number ?? '—' }}</td>
                                <td class="px-6 py-3">@dateboth($doc->expiry_date)</td>
                                <td class="px-6 py-3">
                                    <x-status-pill :status="$doc->status" />
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-8 text-center text-ink-faint">—</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
