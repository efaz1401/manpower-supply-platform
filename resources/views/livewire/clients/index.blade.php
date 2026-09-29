<?php

use App\Models\Client;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $statusFilter = '';

    public ?int $editingId = null;

    // Form fields
    public string $name_en = '';
    public string $name_ar = '';
    public string $cr_number = '';
    public string $establishment_id = '';
    public string $vat_number = '';
    public string $contact_person = '';
    public string $contact_email = '';
    public string $contact_phone = '';
    public string $city = '';
    public string $address = '';
    public int $payment_terms_days = 30;
    public string $status = 'active';
    public string $notes = '';

    public function mount(): void
    {
        $this->authorize('view clients');
    }

    protected function rules(): array
    {
        return [
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'cr_number' => ['nullable', 'string', 'max:255'],
            'establishment_id' => ['nullable', 'string', 'max:255'],
            'vat_number' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'payment_terms_days' => ['required', 'integer', 'min:0', 'max:365'],
            'status' => ['required', 'in:active,inactive,suspended'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('manage clients');
        $this->resetForm();
        $this->dispatch('open-modal', 'client-form');
    }

    public function edit(int $id): void
    {
        $this->authorize('manage clients');
        $client = Client::findOrFail($id);
        $this->editingId = $client->id;
        foreach (array_keys($this->rules()) as $field) {
            $this->{$field} = $client->{$field} ?? $this->{$field};
        }
        $this->dispatch('open-modal', 'client-form');
    }

    public function save(): void
    {
        $this->authorize('manage clients');
        $data = $this->validate();

        if ($this->editingId) {
            Client::findOrFail($this->editingId)->update($data);
        } else {
            Client::create($data);
        }

        $this->dispatch('close-modal', 'client-form');
        $this->dispatch('saved');
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $this->authorize('manage clients');
        Client::findOrFail($id)->delete();
        $this->dispatch('saved');
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'name_en', 'name_ar', 'cr_number', 'establishment_id', 'vat_number',
            'contact_person', 'contact_email', 'contact_phone', 'city', 'address', 'notes',
        ]);
        $this->payment_terms_days = 30;
        $this->status = 'active';
        $this->resetValidation();
    }

    public function with(): array
    {
        $clients = Client::query()
            ->when($this->search, fn ($q) => $q->where(fn ($w) => $w
                ->where('name_en', 'like', "%{$this->search}%")
                ->orWhere('name_ar', 'like', "%{$this->search}%")
                ->orWhere('cr_number', 'like', "%{$this->search}%")))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->withCount(['deployments' => fn ($q) => $q->where('status', 'active')])
            ->latest()
            ->paginate(15);

        return ['clients' => $clients];
    }
}; ?>

<div>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-ink">{{ __('Clients') }}</h2>
            @can('manage clients')
                <x-brand-button type="button" wire:click="create">{{ __('New Client') }}</x-brand-button>
            @endcan
        </div>

        {{-- Filters --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <x-text-input wire:model.live.debounce.300ms="search" type="search"
                          class="w-full sm:max-w-xs" :placeholder="__('Search')" />
            <select wire:model.live="statusFilter"
                    class="bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                <option value="">{{ __('All') }} — {{ __('Status') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="inactive">{{ __('Inactive') }}</option>
                <option value="suspended">{{ __('Suspended') }}</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Name') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('CR Number') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('City') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Active Deployments') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-end font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($clients as $client)
                        <tr wire:key="client-{{ $client->id }}">
                            <td class="px-6 py-3 font-medium text-ink">{{ $client->name() }}</td>
                            <td class="px-6 py-3">{{ $client->cr_number ?? '—' }}</td>
                            <td class="px-6 py-3">{{ $client->city ?? '—' }}</td>
                            <td class="px-6 py-3">{{ $client->deployments_count }}</td>
                            <td class="px-6 py-3"><x-status-pill :status="$client->status" /></td>
                            <td class="px-6 py-3 text-end space-x-2 rtl:space-x-reverse">
                                @can('manage clients')
                                    <button wire:click="edit({{ $client->id }})"
                                            class="text-accent hover:text-success">{{ __('Edit') }}</button>
                                    <button wire:click="delete({{ $client->id }})"
                                            wire:confirm="{{ __('Are you sure?') }}"
                                            class="text-danger hover:text-danger">{{ __('Delete') }}</button>
                                @endcan
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

        <div>{{ $clients->links() }}</div>
    </div>

    {{-- Create / Edit modal --}}
    <x-modal name="client-form" :show="false" focusable maxWidth="2xl">
        <form wire:submit="save" class="p-6 space-y-4">
            <h2 class="text-lg font-semibold text-ink">
                {{ $editingId ? __('Edit') : __('Create') }} — {{ __('Client') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="name_en" :value="__('Name') . ' (EN)'" />
                    <x-text-input wire:model="name_en" id="name_en" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('name_en')" />
                </div>
                <div>
                    <x-input-label for="name_ar" :value="__('Name') . ' (AR)'" />
                    <x-text-input wire:model="name_ar" id="name_ar" class="mt-1 block w-full" dir="rtl" />
                    <x-input-error class="mt-2" :messages="$errors->get('name_ar')" />
                </div>
                <div>
                    <x-input-label for="cr_number" :value="__('CR Number')" />
                    <x-text-input wire:model="cr_number" id="cr_number" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="establishment_id" :value="__('Establishment ID')" />
                    <x-text-input wire:model="establishment_id" id="establishment_id" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="vat_number" :value="__('VAT Number')" />
                    <x-text-input wire:model="vat_number" id="vat_number" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="contact_person" :value="__('Contact Person')" />
                    <x-text-input wire:model="contact_person" id="contact_person" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="contact_email" :value="__('Email')" />
                    <x-text-input wire:model="contact_email" id="contact_email" type="email" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('contact_email')" />
                </div>
                <div>
                    <x-input-label for="contact_phone" :value="__('Phone')" />
                    <x-text-input wire:model="contact_phone" id="contact_phone" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="city" :value="__('City')" />
                    <x-text-input wire:model="city" id="city" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="payment_terms_days" :value="__('Payment Terms (days)')" />
                    <x-text-input wire:model="payment_terms_days" id="payment_terms_days" type="number" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('payment_terms_days')" />
                </div>
                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select wire:model="status" id="status"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="active">{{ __('Active') }}</option>
                        <option value="inactive">{{ __('Inactive') }}</option>
                        <option value="suspended">{{ __('Suspended') }}</option>
                    </select>
                </div>
            </div>

            <div>
                <x-input-label for="address" :value="__('Address')" />
                <textarea wire:model="address" id="address" rows="2"
                          class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'client-form')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-brand-button>{{ __('Save') }}</x-brand-button>
            </div>
        </form>
    </x-modal>
</div>
