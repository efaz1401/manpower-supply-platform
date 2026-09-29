<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">{{ __('Invoices') }}</h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">#</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Issue Date') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Total') ?? 'Total' }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($invoices as $inv)
                        <tr>
                            <td class="px-6 py-3">{{ $inv->invoice_number }}</td>
                            <td class="px-6 py-3">@greg($inv->issue_date)</td>
                            <td class="px-6 py-3">@money($inv->total)</td>
                            <td class="px-6 py-3">{{ __(ucfirst(str_replace('_', ' ', $inv->status))) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-ink-faint">—</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
