<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">{{ __('Payroll') }}</h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 space-y-3 max-w-2xl">
        @forelse ($payslips as $line)
            <div class="bg-surface rounded-lg shadow-glass border border-hairline p-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-ink">
                        {{ $line->payrollRun?->period_month }}/{{ $line->payrollRun?->period_year }}
                    </p>
                    <p class="text-xs text-ink-faint">{{ __(ucfirst($line->status)) }}</p>
                </div>
                <p class="font-semibold text-accent">@money($line->net_pay)</p>
            </div>
        @empty
            <div class="bg-surface rounded-lg shadow-glass border border-hairline p-10 text-center text-ink-faint">—</div>
        @endforelse
    </div>
</x-app-layout>
