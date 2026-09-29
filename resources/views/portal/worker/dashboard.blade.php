<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">{{ __('Worker Portal') }}</h2>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-2xl">
        @if ($worker)
            <div class="bg-surface rounded-lg shadow-glass border border-hairline p-6">
                <p class="text-lg font-semibold text-ink">{{ $worker->name() }}</p>
                <p class="text-sm text-ink-faint">{{ __($worker->profession) }} · {{ $worker->employee_no }}</p>
            </div>

            <div class="bg-surface rounded-lg shadow-glass border border-hairline p-6">
                <h3 class="font-semibold text-ink">{{ __('Deployment') }}</h3>
                @if ($worker->currentDeployment)
                    <p class="mt-2 text-sm text-ink-muted">
                        {{ $worker->currentDeployment->client?->name() }} — {{ $worker->currentDeployment->site }}
                    </p>
                @else
                    <p class="mt-2 text-sm text-ink-faint">{{ __('Available') }}</p>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('worker.documents') }}" class="bg-surface rounded-lg shadow-glass border border-hairline p-6 text-center">
                    <p class="text-2xl font-bold text-accent">{{ $worker->documents->count() }}</p>
                    <p class="text-sm text-ink-faint">{{ __('Documents') }}</p>
                </a>
                <a href="{{ route('worker.payslips') }}" class="bg-surface rounded-lg shadow-glass border border-hairline p-6 text-center">
                    <p class="text-sm text-ink-faint mt-3">{{ __('Payroll') }}</p>
                </a>
            </div>
        @else
            <div class="bg-surface rounded-lg shadow-glass border border-hairline p-10 text-center text-ink-faint">
                {{ __('No worker profile linked to this account.') }}
            </div>
        @endif
    </div>
</x-app-layout>
