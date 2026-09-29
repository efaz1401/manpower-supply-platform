<?php

use App\Models\AlertEvent;
use App\Services\ComplianceScanner;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    #[Url]
    public string $severityFilter = '';

    #[Url]
    public bool $showResolved = false;

    /**
     * Deep-link trigger so the ⌘K palette can run a scan from any page
     * (/compliance?scan=1). The scan is idempotent — repeat hits just
     * re-evaluate thresholds without duplicating alerts.
     */
    #[Url]
    public bool $scan = false;

    public function mount(): void
    {
        $this->authorize('view compliance');

        if ($this->scan) {
            $this->runScan();
        }
    }

    /** Trigger an on-demand compliance scan. */
    public function runScan(): void
    {
        $result = app(ComplianceScanner::class)->scan();
        session()->flash('scan', __(':created new alerts, :resolved resolved.', [
            'created' => $result['alerts_created'],
            'resolved' => $result['alerts_resolved'],
        ]));
        $this->dispatch('saved');
    }

    public function resolve(int $id): void
    {
        AlertEvent::findOrFail($id)->update(['is_resolved' => true, 'resolved_at' => now()]);
        $this->dispatch('saved');
    }

    public function with(): array
    {
        return [
            'alerts' => AlertEvent::query()
                ->with('alertable')
                ->where('is_resolved', $this->showResolved)
                ->when($this->severityFilter, fn ($q) => $q->where('severity', $this->severityFilter))
                // Database-agnostic severity ordering (critical → warning → info).
                ->orderByRaw('CASE severity WHEN ? THEN 1 WHEN ? THEN 2 ELSE 3 END', ['critical', 'warning'])
                ->orderBy('due_date')
                ->paginate(20),
            'counts' => [
                'critical' => AlertEvent::where('is_resolved', false)->where('severity', 'critical')->count(),
                'warning' => AlertEvent::where('is_resolved', false)->where('severity', 'warning')->count(),
                'info' => AlertEvent::where('is_resolved', false)->where('severity', 'info')->count(),
            ],
            'blips' => $this->blips(),
        ];
    }

    /**
     * Plot unresolved alerts onto the radar in polar coordinates.
     *
     * Radius encodes urgency (day 0 at the centre, the 90-day window at the rim);
     * the angle is derived from the record id via the golden angle so blips spread
     * evenly AND stay put across Livewire re-renders (no jitter on DOM morph).
     *
     * @return list<array{key:int,x:float,y:float,severity:string,title:string,days:int}>
     */
    private function blips(): array
    {
        return AlertEvent::query()
            ->where('is_resolved', false)
            ->orderBy('due_date')
            ->limit(60)
            ->get()
            ->map(function (AlertEvent $a) {
                $days = (int) $a->days_remaining;
                // Clamp into the scanner's 90-day window, then map to the ring radii (24..90).
                $ratio = min(max($days, 0), ComplianceScanner::EXPIRING_WINDOW_DAYS)
                    / ComplianceScanner::EXPIRING_WINDOW_DAYS;
                $radius = 24 + ($ratio * 66);
                $angle = deg2rad(fmod($a->id * 137.508, 360)); // golden angle

                return [
                    'key' => $a->id,
                    'x' => round(100 + $radius * cos($angle), 2),
                    'y' => round(100 + $radius * sin($angle), 2),
                    'severity' => $a->severity,
                    'title' => $a->title(),
                    'days' => $days,
                ];
            })
            ->all();
    }
}; ?>

<div>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-ink">{{ __('Compliance Radar') }}</h2>
            <x-brand-button type="button" wire:click="runScan">{{ __('Run Scan') }}</x-brand-button>
        </div>

        <x-compliance-radar :blips="$blips" :counts="$counts">
            @if (session('scan'))
                <div class="rounded-xl border border-accent/25 bg-accent/10 p-3 text-sm text-success animate-fade-in">
                    {{ session('scan') }}
                </div>
            @endif

            <div wire:loading.flex wire:target="runScan" class="items-center gap-2 text-sm text-ink-muted">
                <span class="h-1.5 w-1.5 animate-ping rounded-full bg-accent"></span>
                {{ __('Scanning…') }}
            </div>
        </x-compliance-radar>

        <div class="flex flex-wrap gap-3">
            <select wire:model.live="severityFilter"
                    class="bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                <option value="">{{ __('All') }}</option>
                <option value="critical">{{ __('Critical') }}</option>
                <option value="warning">{{ __('Warning') }}</option>
                <option value="info">{{ __('Info') }}</option>
            </select>
            <label class="inline-flex items-center gap-2 text-sm text-ink-muted">
                <input type="checkbox" wire:model.live="showResolved"
                       class="rounded border-hairline text-accent focus:ring-accent/40" />
                {{ __('Show resolved') }}
            </label>
        </div>

        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Severity') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Title') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Due Date') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Days') }}</th>
                        <th class="px-6 py-3 text-end font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($alerts as $alert)
                        <tr wire:key="alert-{{ $alert->id }}">
                            <td class="px-6 py-3"><x-status-pill :status="$alert->severity === 'critical' ? 'expired' : ($alert->severity === 'warning' ? 'expiring' : 'sourced')" /></td>
                            <td class="px-6 py-3 font-medium text-ink">{{ $alert->title() }}</td>
                            <td class="px-6 py-3">@greg($alert->due_date)</td>
                            <td class="px-6 py-3">{{ $alert->days_remaining }}</td>
                            <td class="px-6 py-3 text-end">
                                @if (! $alert->is_resolved)
                                    <button wire:click="resolve({{ $alert->id }})"
                                            class="text-accent hover:text-success">{{ __('Resolve') }}</button>
                                @else
                                    <span class="text-ink-faint">{{ __('Resolved') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-ink-faint">{{ __('No records found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $alerts->links() }}</div>
    </div>
</div>
