<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">{{ __('Timesheets') }}</h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Worker') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Date') ?? 'Date' }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($timesheets as $t)
                        <tr>
                            <td class="px-6 py-3">{{ $t->worker?->name() }}</td>
                            <td class="px-6 py-3">@greg($t->work_date)</td>
                            <td class="px-6 py-3">{{ __(ucfirst(str_replace('_', ' ', $t->status))) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-ink-faint">—</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
