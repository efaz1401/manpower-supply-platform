<?php

use App\Models\PayrollRun;
use App\Services\PayrollCalculator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public int $period_year;

    public int $period_month;

    public ?int $viewingRunId = null;

    public function mount(): void
    {
        $this->authorize('manage payroll');
        $this->period_year = (int) now()->subMonth()->year;
        $this->period_month = (int) now()->subMonth()->month;
    }

    /** Create a payroll run for the chosen period (idempotent per year+month). */
    public function createRun(): void
    {
        $this->validate([
            'period_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'period_month' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $period = Carbon::create($this->period_year, $this->period_month, 1);

        PayrollRun::firstOrCreate(
            ['period_year' => $this->period_year, 'period_month' => $this->period_month],
            [
                'reference' => 'PR-' . $period->format('Y-m'),
                'period_start' => $period->copy()->startOfMonth(),
                'period_end' => $period->copy()->endOfMonth(),
                'wps_deadline' => $period->copy()->endOfMonth()->addDays(10),
                'status' => 'draft',
                'created_by' => Auth::id(),
            ],
        );

        $this->dispatch('saved');
    }

    /** Run the calculator to (re)generate lines for a run. */
    public function calculate(int $id): void
    {
        $run = PayrollRun::findOrFail($id);
        app(PayrollCalculator::class)->generate($run);
        $this->dispatch('saved');
    }

    public function approve(int $id): void
    {
        PayrollRun::findOrFail($id)->update(['status' => 'approved']);
        $this->dispatch('saved');
    }

    public function view(int $id): void
    {
        $this->viewingRunId = $id;
        $this->dispatch('open-modal', 'payroll-lines');
    }

    public function with(): array
    {
        return [
            'runs' => PayrollRun::withCount('lines')
                ->orderByDesc('period_year')->orderByDesc('period_month')
                ->paginate(12),
            'lines' => $this->viewingRunId
                ? PayrollRun::find($this->viewingRunId)?->lines()->with('worker')->get() ?? collect()
                : collect(),
        ];
    }
}; ?>

<div>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <h2 class="text-xl font-semibold text-ink">{{ __('Payroll') }}</h2>

        {{-- Create run --}}
        <form wire:submit="createRun" class="bg-surface rounded-lg shadow-glass border border-hairline p-5 flex flex-wrap items-end gap-3">
            <div>
                <x-input-label for="period_year" :value="__('Year')" />
                <x-text-input wire:model="period_year" id="period_year" type="number" class="mt-1 w-28" />
            </div>
            <div>
                <x-input-label for="period_month" :value="__('Month')" />
                <x-text-input wire:model="period_month" id="period_month" type="number" min="1" max="12" class="mt-1 w-24" />
            </div>
            <x-brand-button>{{ __('Create Payroll Run') }}</x-brand-button>
        </form>

        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Reference') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Period') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('WPS Deadline') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Lines') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-end font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($runs as $run)
                        <tr wire:key="run-{{ $run->id }}">
                            <td class="px-6 py-3 font-medium text-ink">{{ $run->reference }}</td>
                            <td class="px-6 py-3">{{ $run->period_year }}-{{ str_pad($run->period_month, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-3">@greg($run->wps_deadline)</td>
                            <td class="px-6 py-3">{{ $run->lines_count }}</td>
                            <td class="px-6 py-3"><x-status-pill :status="$run->status" /></td>
                            <td class="px-6 py-3 text-end space-x-2 rtl:space-x-reverse">
                                <button wire:click="calculate({{ $run->id }})"
                                        class="text-info hover:text-info">{{ __('Calculate') }}</button>
                                @if ($run->lines_count > 0)
                                    <button wire:click="view({{ $run->id }})"
                                            class="text-accent hover:text-success">{{ __('View Lines') }}</button>
                                @endif
                                @if ($run->status === 'calculated')
                                    <button wire:click="approve({{ $run->id }})"
                                            class="text-accent hover:text-success font-medium">{{ __('Approve') }}</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-ink-faint">{{ __('No records found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $runs->links() }}</div>
    </div>

    {{-- Payroll lines modal --}}
    <x-modal name="payroll-lines" :show="false" maxWidth="2xl">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-ink mb-4">{{ __('Payroll') }} — {{ __('Lines') }}</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-surface-sunken/60 text-ink-faint">
                        <tr>
                            <th class="px-4 py-2 text-start font-medium">{{ __('Worker') }}</th>
                            <th class="px-4 py-2 text-start font-medium">{{ __('Basic Salary') }}</th>
                            <th class="px-4 py-2 text-start font-medium">{{ __('Overtime') }}</th>
                            <th class="px-4 py-2 text-start font-medium">{{ __('Net Pay') }}</th>
                            <th class="px-4 py-2 text-start font-medium">{{ __('Qiwa') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline">
                        @foreach ($lines as $line)
                            <tr wire:key="line-{{ $line->id }}" class="{{ $line->salary_mismatch ? 'bg-danger/10' : '' }}">
                                <td class="px-4 py-2">{{ $line->worker?->name() }}</td>
                                <td class="px-4 py-2">@money($line->basic_salary)</td>
                                <td class="px-4 py-2">@money($line->overtime_amount)</td>
                                <td class="px-4 py-2 font-medium">@money($line->net_pay)</td>
                                <td class="px-4 py-2">
                                    @money($line->qiwa_contract_salary)
                                    @if ($line->salary_mismatch)
                                        <span class="ms-1 text-xs text-danger">⚠ {{ __('Mismatch') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-4">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'payroll-lines')">
                    {{ __('Close') }}
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</div>
