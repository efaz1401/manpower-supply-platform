<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    #[Url]
    public string $subjectFilter = '';

    public function mount(): void
    {
        $this->authorize('view activity log');
    }

    public function with(): array
    {
        return [
            'activities' => Activity::query()
                ->with('causer')
                ->when($this->subjectFilter, fn ($q) => $q->where('subject_type', 'like', "%{$this->subjectFilter}%"))
                ->latest()
                ->paginate(25),
            'subjectTypes' => Activity::query()
                ->select('subject_type')->whereNotNull('subject_type')->distinct()->pluck('subject_type'),
        ];
    }
}; ?>

<div>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <h2 class="text-xl font-semibold text-ink">{{ __('Activity Log') }}</h2>

        <div class="flex gap-3">
            <select wire:model.live="subjectFilter"
                    class="bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                <option value="">{{ __('All') }}</option>
                @foreach ($subjectTypes as $type)
                    <option value="{{ $type }}">{{ class_basename($type) }}</option>
                @endforeach
            </select>
        </div>

        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Date') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Description') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Subject') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('User') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($activities as $activity)
                        <tr wire:key="activity-{{ $activity->id }}">
                            <td class="px-6 py-3 text-ink-faint">{{ $activity->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-3">{{ __(ucfirst($activity->description)) }}</td>
                            <td class="px-6 py-3">
                                {{ $activity->subject_type ? class_basename($activity->subject_type) : '—' }}
                                @if ($activity->subject_id) #{{ $activity->subject_id }} @endif
                            </td>
                            <td class="px-6 py-3">{{ $activity->causer?->name ?? __('System') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-ink-faint">{{ __('No records found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $activities->links() }}</div>
    </div>
</div>
