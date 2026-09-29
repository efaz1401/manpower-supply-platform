<?php

use App\Models\AjeerPermit;
use App\Models\Client;
use App\Models\Deployment;
use App\Models\RateCard;
use App\Models\Worker;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    #[Url]
    public string $statusFilter = '';

    public ?int $editingId = null;

    // Form fields
    public $worker_id = '';
    public $client_id = '';
    public $ajeer_permit_id = '';
    public string $profession = '';
    public string $site = '';
    public string $start_date = '';
    public string $end_date = '';
    public $billing_rate = 0;
    public string $billing_cycle = 'monthly';
    public string $status = 'pending';
    public string $notes = '';

    public function mount(): void
    {
        $this->authorize('manage deployments');
    }

    protected function rules(): array
    {
        return [
            'worker_id' => ['required', 'exists:workers,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'ajeer_permit_id' => ['nullable', 'exists:ajeer_permits,id'],
            'profession' => ['required', 'string', 'max:255'],
            'site' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'billing_rate' => ['required', 'numeric', 'min:0'],
            'billing_cycle' => ['required', 'in:monthly,hourly'],
            'status' => ['required', 'in:pending,permit_issued,active,ended'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'deployment-form');
    }

    public function edit(int $id): void
    {
        $deployment = Deployment::findOrFail($id);
        $this->editingId = $deployment->id;
        foreach (array_keys($this->rules()) as $field) {
            $value = $deployment->{$field};
            $this->{$field} = $value instanceof \Carbon\CarbonInterface ? $value->toDateString() : ($value ?? $this->{$field});
        }
        $this->dispatch('open-modal', 'deployment-form');
    }

    /** When a worker is picked, default the profession + rate from their profile / rate card. */
    public function updatedWorkerId($value): void
    {
        if ($worker = Worker::find($value)) {
            $this->profession = $worker->profession;
            $this->applyRate();
        }
    }

    public function updatedClientId(): void
    {
        $this->applyRate();
    }

    private function applyRate(): void
    {
        if ($this->client_id && $this->profession) {
            $rate = RateCard::where('client_id', $this->client_id)
                ->where('profession', $this->profession)
                ->value('monthly_rate');
            if ($rate) {
                $this->billing_rate = $rate;
            }
        }
    }

    public function save(): void
    {
        $data = $this->validate();

        // Business rule: the deployment profession must match the worker's profession.
        $worker = Worker::findOrFail($data['worker_id']);
        if ($data['profession'] !== $worker->profession) {
            $this->addError('profession', __('Profession must match the worker\'s registered profession.'));

            return;
        }

        // Business rule: activating a deployment requires a valid Ajeer permit.
        if ($data['status'] === 'active') {
            $permit = $data['ajeer_permit_id'] ? AjeerPermit::find($data['ajeer_permit_id']) : null;
            if (! $permit || ! $permit->isValid()) {
                $this->addError('ajeer_permit_id', __('An active Ajeer permit is required to activate a deployment.'));

                return;
            }
        }

        if ($this->editingId) {
            $deployment = Deployment::findOrFail($this->editingId);
            $deployment->update($data);
        } else {
            $deployment = Deployment::create($data);
        }

        // Sync the worker's availability with the deployment lifecycle.
        if ($data['status'] === 'active') {
            $worker->update(['availability' => 'deployed']);
        } elseif ($data['status'] === 'ended' && $worker->availability === 'deployed') {
            $worker->update(['availability' => 'available']);
        }

        $this->dispatch('close-modal', 'deployment-form');
        $this->dispatch('saved');
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Deployment::findOrFail($id)->delete();
        $this->dispatch('saved');
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'worker_id', 'client_id', 'ajeer_permit_id', 'profession',
            'site', 'start_date', 'end_date', 'notes',
        ]);
        $this->billing_rate = 0;
        $this->billing_cycle = 'monthly';
        $this->status = 'pending';
        $this->resetValidation();
    }

    public function with(): array
    {
        return [
            'deployments' => Deployment::query()
                ->with(['worker', 'client', 'ajeerPermit'])
                ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
                ->latest()
                ->paginate(15),
            'workers' => Worker::orderBy('name_en')->get(['id', 'name_en', 'name_ar', 'profession']),
            'clients' => Client::orderBy('name_en')->get(['id', 'name_en', 'name_ar']),
            'permits' => $this->client_id && $this->worker_id
                ? AjeerPermit::where('worker_id', $this->worker_id)->where('client_id', $this->client_id)->get()
                : collect(),
        ];
    }
}; ?>

<div>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-ink">{{ __('Deployments') }}</h2>
            <x-brand-button type="button" wire:click="create">{{ __('New Deployment') }}</x-brand-button>
        </div>

        <div class="flex gap-3">
            <select wire:model.live="statusFilter"
                    class="bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                <option value="">{{ __('All') }}</option>
                <option value="pending">{{ __('Pending') }}</option>
                <option value="permit_issued">{{ __('Permit Issued') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="ended">{{ __('Ended') }}</option>
            </select>
        </div>

        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Worker') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Client') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Profession') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Site') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Start Date') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-end font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($deployments as $d)
                        <tr wire:key="deployment-{{ $d->id }}">
                            <td class="px-6 py-3 font-medium text-ink">{{ $d->worker?->name() }}</td>
                            <td class="px-6 py-3">{{ $d->client?->name() }}</td>
                            <td class="px-6 py-3">{{ __($d->profession) }}</td>
                            <td class="px-6 py-3">{{ $d->site ?? '—' }}</td>
                            <td class="px-6 py-3">@greg($d->start_date)</td>
                            <td class="px-6 py-3"><x-status-pill :status="$d->status" /></td>
                            <td class="px-6 py-3 text-end space-x-2 rtl:space-x-reverse">
                                <button wire:click="edit({{ $d->id }})"
                                        class="text-accent hover:text-success">{{ __('Edit') }}</button>
                                <button wire:click="delete({{ $d->id }})"
                                        wire:confirm="{{ __('Are you sure?') }}"
                                        class="text-danger hover:text-danger">{{ __('Delete') }}</button>
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

        <div>{{ $deployments->links() }}</div>
    </div>

    <x-modal name="deployment-form" :show="false" focusable maxWidth="2xl">
        <form wire:submit="save" class="p-6 space-y-4">
            <h2 class="text-lg font-semibold text-ink">
                {{ $editingId ? __('Edit') : __('Create') }} — {{ __('Deployment') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="worker_id" :value="__('Worker')" />
                    <select wire:model.live="worker_id" id="worker_id"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="">— {{ __('Select') }} —</option>
                        @foreach ($workers as $w)
                            <option value="{{ $w->id }}">{{ $w->name() }} ({{ __($w->profession) }})</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('worker_id')" />
                </div>
                <div>
                    <x-input-label for="client_id" :value="__('Client')" />
                    <select wire:model.live="client_id" id="client_id"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="">— {{ __('Select') }} —</option>
                        @foreach ($clients as $c)
                            <option value="{{ $c->id }}">{{ $c->name() }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('client_id')" />
                </div>
                <div>
                    <x-input-label for="d_profession" :value="__('Profession')" />
                    <x-text-input wire:model="profession" id="d_profession" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('profession')" />
                </div>
                <div>
                    <x-input-label for="ajeer_permit_id" :value="__('Ajeer Permit')" />
                    <select wire:model="ajeer_permit_id" id="ajeer_permit_id"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="">— {{ __('None') }} —</option>
                        @foreach ($permits as $p)
                            <option value="{{ $p->id }}">{{ $p->permit_number }} ({{ __(ucfirst($p->status)) }})</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('ajeer_permit_id')" />
                </div>
                <div>
                    <x-input-label for="site" :value="__('Site')" />
                    <x-text-input wire:model="site" id="site" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select wire:model="status" id="status"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="pending">{{ __('Pending') }}</option>
                        <option value="permit_issued">{{ __('Permit Issued') }}</option>
                        <option value="active">{{ __('Active') }}</option>
                        <option value="ended">{{ __('Ended') }}</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="start_date" :value="__('Start Date')" />
                    <x-text-input wire:model="start_date" id="start_date" type="date" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="end_date" :value="__('End Date')" />
                    <x-text-input wire:model="end_date" id="end_date" type="date" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                </div>
                <div>
                    <x-input-label for="billing_rate" :value="__('Billing Rate')" />
                    <x-text-input wire:model="billing_rate" id="billing_rate" type="number" step="0.01" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('billing_rate')" />
                </div>
                <div>
                    <x-input-label for="billing_cycle" :value="__('Billing Cycle')" />
                    <select wire:model="billing_cycle" id="billing_cycle"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="monthly">{{ __('Monthly') }}</option>
                        <option value="hourly">{{ __('Hourly') }}</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'deployment-form')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-brand-button>{{ __('Save') }}</x-brand-button>
            </div>
        </form>
    </x-modal>
</div>
