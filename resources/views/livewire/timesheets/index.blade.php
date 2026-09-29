<?php

use App\Models\Timesheet;
use App\Services\TimesheetCalculator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    #[Url]
    public string $statusFilter = '';

    public function mount(): void
    {
        $this->authorize('manage timesheets');
    }

    /** Ops verifies a client-approved (or submitted) timesheet. */
    public function verify(int $id): void
    {
        $this->authorize('approve timesheets');
        $ts = Timesheet::findOrFail($id);
        // Ensure hours are current before locking the record.
        app(TimesheetCalculator::class)->recalculate($ts);
        $ts->update(['status' => 'verified', 'verified_by' => Auth::id()]);
        $this->dispatch('saved');
    }

    public function reject(int $id): void
    {
        $this->authorize('approve timesheets');
        Timesheet::findOrFail($id)->update(['status' => 'rejected']);
        $this->dispatch('saved');
    }

    /** Recompute hours/overtime from the clock window. */
    public function recalculate(int $id): void
    {
        $ts = Timesheet::findOrFail($id);
        app(TimesheetCalculator::class)->recalculate($ts);
        $this->dispatch('saved');
    }

    public function with(): array
    {
        return [
            'timesheets' => Timesheet::query()
                ->with(['worker', 'deployment.client'])
                ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
                ->latest('work_date')
                ->paginate(20),
        ];
    }
}; ?>

<div>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <h2 class="text-xl font-semibold text-ink">{{ __('Timesheets') }}</h2>

        <div class="flex gap-3">
            <select wire:model.live="statusFilter"
                    class="bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                <option value="">{{ __('All') }}</option>
                <option value="draft">{{ __('Draft') }}</option>
                <option value="submitted">{{ __('Submitted') }}</option>
                <option value="client_approved">{{ __('Client Approved') }}</option>
                <option value="verified">{{ __('Verified') }}</option>
                <option value="rejected">{{ __('Rejected') }}</option>
            </select>
        </div>

        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Worker') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Client') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Hours') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Overtime') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-end font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($timesheets as $ts)
                        <tr wire:key="ts-{{ $ts->id }}">
                            <td class="px-6 py-3 font-medium text-ink">{{ $ts->worker?->name() }}</td>
                            <td class="px-6 py-3">{{ $ts->deployment?->client?->name() ?? '—' }}</td>
                            <td class="px-6 py-3">@greg($ts->work_date)</td>
                            <td class="px-6 py-3">{{ $ts->hours }}</td>
                            <td class="px-6 py-3">{{ $ts->overtime_hours }}</td>
                            <td class="px-6 py-3"><x-status-pill :status="$ts->status" /></td>
                            <td class="px-6 py-3 text-end space-x-2 rtl:space-x-reverse">
                                <button wire:click="recalculate({{ $ts->id }})"
                                        class="text-ink-faint hover:text-ink-muted">{{ __('Recalculate') }}</button>
                                @can('approve timesheets')
                                    @if (in_array($ts->status, ['submitted', 'client_approved']))
                                        <button wire:click="verify({{ $ts->id }})"
                                                class="text-accent hover:text-success font-medium">{{ __('Verify') }}</button>
                                        <button wire:click="reject({{ $ts->id }})"
                                                class="text-danger hover:text-danger">{{ __('Reject') }}</button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-ink-faint">{{ __('No records found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $timesheets->links() }}</div>
    </div>
</div>
