<?php

use App\Models\RecruitmentCandidate;
use App\Models\Worker;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public const STAGES = ['sourced', 'offered', 'visa', 'contract', 'medical', 'travel', 'arrived', 'onboarded', 'rejected'];

    #[Url]
    public string $stageFilter = '';

    public ?int $editingId = null;

    public string $name_en = '';
    public string $name_ar = '';
    public string $nationality = '';
    public string $profession = '';
    public string $phone = '';
    public string $email = '';
    public string $source = '';
    public string $stage = 'sourced';
    public string $notes = '';

    public function mount(): void
    {
        $this->authorize('manage recruitment');
    }

    protected function rules(): array
    {
        return [
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'stage' => ['required', 'in:'.implode(',', self::STAGES)],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'candidate-form');
    }

    public function edit(int $id): void
    {
        $candidate = RecruitmentCandidate::findOrFail($id);
        $this->editingId = $candidate->id;
        foreach (array_keys($this->rules()) as $field) {
            $this->{$field} = $candidate->{$field} ?? $this->{$field};
        }
        $this->dispatch('open-modal', 'candidate-form');
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->editingId) {
            RecruitmentCandidate::findOrFail($this->editingId)->update($data);
        } else {
            RecruitmentCandidate::create($data);
        }

        $this->dispatch('close-modal', 'candidate-form');
        $this->dispatch('saved');
        $this->resetForm();
    }

    /** Advance a candidate one step along the pipeline. */
    public function advance(int $id): void
    {
        $candidate = RecruitmentCandidate::findOrFail($id);
        $idx = array_search($candidate->stage, self::STAGES, true);
        // Do not auto-advance past 'arrived'; 'onboarded' happens via conversion.
        if ($idx !== false && $idx < array_search('arrived', self::STAGES, true)) {
            $candidate->update(['stage' => self::STAGES[$idx + 1]]);
            $this->dispatch('saved');
        }
    }

    /** Convert an arrived candidate into a Worker record and mark them onboarded. */
    public function convert(int $id): void
    {
        $this->authorize('manage workers');
        $candidate = RecruitmentCandidate::findOrFail($id);

        if ($candidate->converted_worker_id) {
            return;
        }

        $worker = Worker::create([
            'name_en' => $candidate->name_en,
            'name_ar' => $candidate->name_ar ?: $candidate->name_en,
            'nationality' => $candidate->nationality,
            'profession' => $candidate->profession ?: 'General',
            'phone' => $candidate->phone,
            'availability' => 'available',
        ]);

        $candidate->update(['converted_worker_id' => $worker->id, 'stage' => 'onboarded']);
        $this->dispatch('saved');
    }

    public function delete(int $id): void
    {
        RecruitmentCandidate::findOrFail($id)->delete();
        $this->dispatch('saved');
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'name_en', 'name_ar', 'nationality', 'profession',
            'phone', 'email', 'source', 'notes',
        ]);
        $this->stage = 'sourced';
        $this->resetValidation();
    }

    public function with(): array
    {
        return [
            'candidates' => RecruitmentCandidate::query()
                ->when($this->stageFilter, fn ($q) => $q->where('stage', $this->stageFilter))
                ->latest()
                ->paginate(15),
            'stages' => self::STAGES,
        ];
    }
}; ?>

<div>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-ink">{{ __('Recruitment') }}</h2>
            <x-brand-button type="button" wire:click="create">{{ __('New Candidate') }}</x-brand-button>
        </div>

        <div class="flex gap-3">
            <select wire:model.live="stageFilter"
                    class="bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                <option value="">{{ __('All') }}</option>
                @foreach ($stages as $s)
                    <option value="{{ $s }}">{{ __(ucfirst($s)) }}</option>
                @endforeach
            </select>
        </div>

        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Name') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Profession') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Nationality') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Stage') }}</th>
                        <th class="px-6 py-3 text-end font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($candidates as $c)
                        <tr wire:key="candidate-{{ $c->id }}">
                            <td class="px-6 py-3 font-medium text-ink">
                                {{ app()->getLocale() === 'ar' && $c->name_ar ? $c->name_ar : $c->name_en }}
                            </td>
                            <td class="px-6 py-3">{{ $c->profession ? __($c->profession) : '—' }}</td>
                            <td class="px-6 py-3">{{ $c->nationality ? __($c->nationality) : '—' }}</td>
                            <td class="px-6 py-3"><x-status-pill :status="$c->stage" /></td>
                            <td class="px-6 py-3 text-end space-x-2 rtl:space-x-reverse">
                                @if (! in_array($c->stage, ['arrived', 'onboarded', 'rejected']))
                                    <button wire:click="advance({{ $c->id }})"
                                            class="text-info hover:text-info">{{ __('Advance') }}</button>
                                @endif
                                @if ($c->stage === 'arrived' && ! $c->converted_worker_id)
                                    <button wire:click="convert({{ $c->id }})"
                                            wire:confirm="{{ __('Convert this candidate into a worker?') }}"
                                            class="text-accent hover:text-success font-medium">{{ __('Convert to Worker') }}</button>
                                @endif
                                <button wire:click="edit({{ $c->id }})"
                                        class="text-accent hover:text-success">{{ __('Edit') }}</button>
                                <button wire:click="delete({{ $c->id }})"
                                        wire:confirm="{{ __('Are you sure?') }}"
                                        class="text-danger hover:text-danger">{{ __('Delete') }}</button>
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

        <div>{{ $candidates->links() }}</div>
    </div>

    <x-modal name="candidate-form" :show="false" focusable maxWidth="lg">
        <form wire:submit="save" class="p-6 space-y-4">
            <h2 class="text-lg font-semibold text-ink">
                {{ $editingId ? __('Edit') : __('Create') }} — {{ __('Candidate') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="c_name_en" :value="__('Name') . ' (EN)'" />
                    <x-text-input wire:model="name_en" id="c_name_en" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('name_en')" />
                </div>
                <div>
                    <x-input-label for="c_name_ar" :value="__('Name') . ' (AR)'" />
                    <x-text-input wire:model="name_ar" id="c_name_ar" class="mt-1 block w-full" dir="rtl" />
                </div>
                <div>
                    <x-input-label for="c_profession" :value="__('Profession')" />
                    <x-text-input wire:model="profession" id="c_profession" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="c_nationality" :value="__('Nationality')" />
                    <x-text-input wire:model="nationality" id="c_nationality" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="c_phone" :value="__('Phone')" />
                    <x-text-input wire:model="phone" id="c_phone" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="c_email" :value="__('Email')" />
                    <x-text-input wire:model="email" id="c_email" type="email" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>
                <div>
                    <x-input-label for="c_source" :value="__('Source')" />
                    <x-text-input wire:model="source" id="c_source" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="c_stage" :value="__('Stage')" />
                    <select wire:model="stage" id="c_stage"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        @foreach ($stages as $s)
                            <option value="{{ $s }}">{{ __(ucfirst($s)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'candidate-form')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-brand-button>{{ __('Save') }}</x-brand-button>
            </div>
        </form>
    </x-modal>
</div>
