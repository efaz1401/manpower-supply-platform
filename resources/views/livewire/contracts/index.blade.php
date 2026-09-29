<?php

use App\Models\Client;
use App\Models\SupplyContract;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    #[Url]
    public string $statusFilter = '';

    public ?int $editingId = null;

    public $client_id = '';
    public string $reference = '';
    public string $title_en = '';
    public string $title_ar = '';
    public string $professions = ''; // comma-separated in the form
    public int $headcount_quota = 0;
    public string $start_date = '';
    public string $end_date = '';
    public bool $auto_renew = false;
    public string $status = 'draft';
    public string $notes = '';

    public function mount(): void
    {
        $this->authorize('manage contracts');
    }

    protected function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'reference' => ['nullable', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'professions' => ['nullable', 'string', 'max:1000'],
            'headcount_quota' => ['required', 'integer', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'auto_renew' => ['boolean'],
            'status' => ['required', 'in:draft,active,expired,terminated'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'contract-form');
    }

    public function edit(int $id): void
    {
        $contract = SupplyContract::findOrFail($id);
        $this->editingId = $contract->id;
        $this->client_id = $contract->client_id;
        $this->reference = $contract->reference ?? '';
        $this->title_en = $contract->title_en ?? '';
        $this->title_ar = $contract->title_ar ?? '';
        $this->professions = implode(', ', $contract->professions ?? []);
        $this->headcount_quota = $contract->headcount_quota;
        $this->start_date = $contract->start_date?->toDateString() ?? '';
        $this->end_date = $contract->end_date?->toDateString() ?? '';
        $this->auto_renew = (bool) $contract->auto_renew;
        $this->status = $contract->status;
        $this->notes = $contract->notes ?? '';
        $this->dispatch('open-modal', 'contract-form');
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['professions'] = collect(explode(',', $this->professions))
            ->map(fn ($p) => trim($p))->filter()->values()->all();

        if ($this->editingId) {
            SupplyContract::findOrFail($this->editingId)->update($data);
        } else {
            SupplyContract::create($data);
        }

        $this->dispatch('close-modal', 'contract-form');
        $this->dispatch('saved');
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        SupplyContract::findOrFail($id)->delete();
        $this->dispatch('saved');
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'client_id', 'reference', 'title_en', 'title_ar',
            'professions', 'start_date', 'end_date', 'notes',
        ]);
        $this->headcount_quota = 0;
        $this->auto_renew = false;
        $this->status = 'draft';
        $this->resetValidation();
    }

    public function with(): array
    {
        return [
            'contracts' => SupplyContract::query()
                ->with('client')
                ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
                ->latest()
                ->paginate(15),
            'clients' => Client::orderBy('name_en')->get(['id', 'name_en', 'name_ar']),
        ];
    }
}; ?>

<div>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-ink">{{ __('Supply Contracts') }}</h2>
            <x-brand-button type="button" wire:click="create">{{ __('New Contract') }}</x-brand-button>
        </div>

        <div class="flex gap-3">
            <select wire:model.live="statusFilter"
                    class="bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                <option value="">{{ __('All') }}</option>
                <option value="draft">{{ __('Draft') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="expired">{{ __('Expired') }}</option>
                <option value="terminated">{{ __('Terminated') }}</option>
            </select>
        </div>

        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Reference') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Client') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Headcount Quota') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('End Date') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-end font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($contracts as $contract)
                        <tr wire:key="contract-{{ $contract->id }}">
                            <td class="px-6 py-3 font-medium text-ink">{{ $contract->reference ?? '—' }}</td>
                            <td class="px-6 py-3">{{ $contract->client?->name() }}</td>
                            <td class="px-6 py-3">{{ $contract->headcount_quota }}</td>
                            <td class="px-6 py-3">@greg($contract->end_date)</td>
                            <td class="px-6 py-3"><x-status-pill :status="$contract->status" /></td>
                            <td class="px-6 py-3 text-end space-x-2 rtl:space-x-reverse">
                                <button wire:click="edit({{ $contract->id }})"
                                        class="text-accent hover:text-success">{{ __('Edit') }}</button>
                                <button wire:click="delete({{ $contract->id }})"
                                        wire:confirm="{{ __('Are you sure?') }}"
                                        class="text-danger hover:text-danger">{{ __('Delete') }}</button>
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

        <div>{{ $contracts->links() }}</div>
    </div>

    <x-modal name="contract-form" :show="false" focusable maxWidth="2xl">
        <form wire:submit="save" class="p-6 space-y-4">
            <h2 class="text-lg font-semibold text-ink">
                {{ $editingId ? __('Edit') : __('Create') }} — {{ __('Supply Contracts') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="sc_client_id" :value="__('Client')" />
                    <select wire:model="client_id" id="sc_client_id"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="">— {{ __('Select') }} —</option>
                        @foreach ($clients as $c)
                            <option value="{{ $c->id }}">{{ $c->name() }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
                </div>
                <div>
                    <x-input-label for="sc_reference" :value="__('Reference')" />
                    <x-text-input wire:model="reference" id="sc_reference" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="sc_title_en" :value="__('Title') . ' (EN)'" />
                    <x-text-input wire:model="title_en" id="sc_title_en" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="sc_title_ar" :value="__('Title') . ' (AR)'" />
                    <x-text-input wire:model="title_ar" id="sc_title_ar" class="mt-1 block w-full" dir="rtl" />
                </div>
                <div class="sm:col-span-2">
                    <x-input-label for="sc_professions" :value="__('Professions') . ' (' . __('comma-separated') . ')'" />
                    <x-text-input wire:model="professions" id="sc_professions" class="mt-1 block w-full" placeholder="Mason, Electrician, Driver" />
                </div>
                <div>
                    <x-input-label for="sc_headcount" :value="__('Headcount Quota')" />
                    <x-text-input wire:model="headcount_quota" id="sc_headcount" type="number" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('headcount_quota')" />
                </div>
                <div>
                    <x-input-label for="sc_status" :value="__('Status')" />
                    <select wire:model="status" id="sc_status"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="draft">{{ __('Draft') }}</option>
                        <option value="active">{{ __('Active') }}</option>
                        <option value="expired">{{ __('Expired') }}</option>
                        <option value="terminated">{{ __('Terminated') }}</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="sc_start" :value="__('Start Date')" />
                    <x-text-input wire:model="start_date" id="sc_start" type="date" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="sc_end" :value="__('End Date')" />
                    <x-text-input wire:model="end_date" id="sc_end" type="date" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                </div>
                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" wire:model="auto_renew" id="sc_auto_renew"
                           class="rounded border-hairline text-accent focus:ring-accent/40" />
                    <x-input-label for="sc_auto_renew" :value="__('Auto Renew')" class="!mt-0" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'contract-form')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-brand-button>{{ __('Save') }}</x-brand-button>
            </div>
        </form>
    </x-modal>
</div>
