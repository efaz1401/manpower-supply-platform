<?php

use App\Models\Worker;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $availabilityFilter = '';

    public ?int $editingId = null;

    // Form fields
    public string $employee_no = '';
    public string $name_en = '';
    public string $name_ar = '';
    public string $nationality = '';
    public string $profession = '';
    public string $iqama_number = '';
    public string $passport_number = '';
    public string $gender = 'male';
    public string $sponsorship_status = 'sponsored';
    public string $availability = 'available';
    public string $payment_method = 'bank_transfer';
    public string $bank_name = '';
    public string $iban = '';
    public $qiwa_contract_salary = 0;
    public $basic_salary = 0;
    public string $phone = '';
    public string $notes = '';

    public function mount(): void
    {
        $this->authorize('view workers');
    }

    protected function rules(): array
    {
        return [
            'employee_no' => ['nullable', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'profession' => ['required', 'string', 'max:255'],
            'iqama_number' => ['nullable', 'string', 'max:255'],
            'passport_number' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female'],
            'sponsorship_status' => ['required', 'in:sponsored,transferred_out,exited'],
            'availability' => ['required', 'in:available,deployed,on_leave,exiting'],
            'payment_method' => ['required', 'in:bank_transfer,payroll_card'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'iban' => ['nullable', 'string', 'max:34'],
            'qiwa_contract_salary' => ['required', 'numeric', 'min:0'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'phone' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('manage workers');
        $this->resetForm();
        $this->dispatch('open-modal', 'worker-form');
    }

    public function edit(int $id): void
    {
        $this->authorize('manage workers');
        $worker = Worker::findOrFail($id);
        $this->editingId = $worker->id;
        foreach (array_keys($this->rules()) as $field) {
            $this->{$field} = $worker->{$field} ?? $this->{$field};
        }
        $this->dispatch('open-modal', 'worker-form');
    }

    public function save(): void
    {
        $this->authorize('manage workers');
        $data = $this->validate();

        if ($this->editingId) {
            Worker::findOrFail($this->editingId)->update($data);
        } else {
            Worker::create($data);
        }

        $this->dispatch('close-modal', 'worker-form');
        $this->dispatch('saved');
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $this->authorize('manage workers');
        Worker::findOrFail($id)->delete();
        $this->dispatch('saved');
    }

    /*
    |------------------------------------------------------------------
    | Bulk selection & editing
    |------------------------------------------------------------------
    */

    /** IDs of workers ticked for a bulk action. */
    public array $selected = [];

    /** Header "select all on this page" toggle. */
    public bool $selectPage = false;

    /** Bulk-edit form state. */
    public bool $bulkConfirming = false;

    /** Which fields the bulk edit should apply (per-field opt-in). */
    public array $bulkApply = [
        'nationality' => false, 'profession' => false, 'gender' => false,
        'sponsorship_status' => false, 'availability' => false,
        'payment_method' => false, 'bank_name' => false, 'notes' => false,
    ];

    /** Values to apply for the fields opted in above. */
    public array $bulk = [
        'nationality' => '', 'profession' => '', 'gender' => 'male',
        'sponsorship_status' => 'sponsored', 'availability' => 'available',
        'payment_method' => 'bank_transfer', 'bank_name' => '', 'notes' => '',
    ];

    /** Fields that are safe to change across many workers at once. */
    private function bulkEditableFields(): array
    {
        return ['nationality', 'profession', 'gender', 'sponsorship_status',
            'availability', 'payment_method', 'bank_name', 'notes'];
    }

    /** Keep the header checkbox in sync with the page's selection. */
    public function updatedSelectPage(bool $value): void
    {
        $ids = $this->pageWorkerIds();
        $this->selected = $value
            ? array_values(array_unique(array_merge($this->selected, $ids)))
            : array_values(array_diff($this->selected, $ids));
    }

    /** The worker IDs currently visible on this page. */
    private function pageWorkerIds(): array
    {
        return Worker::query()
            ->when($this->search, fn ($q) => $q->where(fn ($w) => $w
                ->where('name_en', 'like', "%{$this->search}%")
                ->orWhere('name_ar', 'like', "%{$this->search}%")
                ->orWhere('employee_no', 'like', "%{$this->search}%")
                ->orWhere('iqama_number', 'like', "%{$this->search}%")))
            ->when($this->availabilityFilter, fn ($q) => $q->where('availability', $this->availabilityFilter))
            ->latest()
            ->forPage($this->getPage(), 15)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function clearSelection(): void
    {
        $this->selected = [];
        $this->selectPage = false;
    }

    public function bulkEdit(): void
    {
        $this->authorize('manage workers');

        if (empty($this->selected)) {
            return;
        }

        $this->bulkConfirming = false;
        $this->reset('bulkApply', 'bulk');
        $this->resetValidation();
        $this->dispatch('open-modal', 'worker-bulk-form');
    }

    /** First "Apply" click → validate the opted-in fields and show a confirm summary. */
    public function bulkReview(): void
    {
        $this->authorize('manage workers');

        $applied = array_keys(array_filter($this->bulkApply));

        if (empty($applied)) {
            $this->addError('bulkApply', __('Select at least one field to change.'));

            return;
        }

        $this->validate($this->bulkRules($applied));
        $this->bulkConfirming = true;
    }

    /** Confirmed → write the opted-in fields to every selected worker. */
    public function bulkSave(): void
    {
        $this->authorize('manage workers');

        $applied = array_keys(array_filter($this->bulkApply));
        if (empty($applied) || empty($this->selected)) {
            return;
        }

        $this->validate($this->bulkRules($applied));

        $data = [];
        foreach ($applied as $field) {
            $data[$field] = $this->bulk[$field];
        }

        Worker::whereIn('id', $this->selected)->update($data);

        $count = count($this->selected);
        $this->dispatch('close-modal', 'worker-bulk-form');
        $this->dispatch('saved');
        $this->clearSelection();
        $this->bulkConfirming = false;
        session()->flash('bulk', __(':count workers updated.', ['count' => $count]));
    }

    /** Validation limited to the fields being applied. */
    private function bulkRules(array $applied): array
    {
        $all = [
            'bulk.nationality' => ['nullable', 'string', 'max:255'],
            'bulk.profession' => ['required', 'string', 'max:255'],
            'bulk.gender' => ['required', 'in:male,female'],
            'bulk.sponsorship_status' => ['required', 'in:sponsored,transferred_out,exited'],
            'bulk.availability' => ['required', 'in:available,deployed,on_leave,exiting'],
            'bulk.payment_method' => ['required', 'in:bank_transfer,payroll_card'],
            'bulk.bank_name' => ['nullable', 'string', 'max:255'],
            'bulk.notes' => ['nullable', 'string', 'max:2000'],
        ];

        return collect($applied)
            ->mapWithKeys(fn ($f) => ["bulk.$f" => $all["bulk.$f"]])
            ->all();
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'employee_no', 'name_en', 'name_ar', 'nationality', 'profession',
            'iqama_number', 'passport_number', 'bank_name', 'iban', 'phone', 'notes',
        ]);
        $this->gender = 'male';
        $this->sponsorship_status = 'sponsored';
        $this->availability = 'available';
        $this->payment_method = 'bank_transfer';
        $this->qiwa_contract_salary = 0;
        $this->basic_salary = 0;
        $this->resetValidation();
    }

    public function with(): array
    {
        $workers = Worker::query()
            ->when($this->search, fn ($q) => $q->where(fn ($w) => $w
                ->where('name_en', 'like', "%{$this->search}%")
                ->orWhere('name_ar', 'like', "%{$this->search}%")
                ->orWhere('employee_no', 'like', "%{$this->search}%")
                ->orWhere('iqama_number', 'like', "%{$this->search}%")))
            ->when($this->availabilityFilter, fn ($q) => $q->where('availability', $this->availabilityFilter))
            ->latest()
            ->paginate(15);

        return ['workers' => $workers];
    }
}; ?>

<div>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-ink">{{ __('Workers') }}</h2>
            @can('manage workers')
                <x-brand-button type="button" wire:click="create">{{ __('New Worker') }}</x-brand-button>
            @endcan
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <x-text-input wire:model.live.debounce.300ms="search" type="search"
                          class="w-full sm:max-w-xs" :placeholder="__('Search')" />
            <select wire:model.live="availabilityFilter"
                    class="bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                <option value="">{{ __('All') }}</option>
                <option value="available">{{ __('Available') }}</option>
                <option value="deployed">{{ __('Deployed') }}</option>
                <option value="on_leave">{{ __('On Leave') }}</option>
                <option value="exiting">{{ __('Exiting') }}</option>
            </select>
        </div>

        @if (session('bulk'))
            <div class="rounded-md bg-accent/10 border border-accent/25 p-3 text-sm text-success">
                {{ session('bulk') }}
            </div>
        @endif

        @can('manage workers')
            @if (count($selected) > 0)
                <div class="flex items-center justify-between rounded-md bg-accent text-accent-contrast px-4 py-2 text-sm">
                    <span>{{ __(':count selected', ['count' => count($selected)]) }}</span>
                    <div class="flex items-center gap-3">
                        <button type="button" wire:click="bulkEdit"
                                class="font-medium underline-offset-2 hover:underline">{{ __('Bulk Edit') }}</button>
                        <button type="button" wire:click="clearSelection"
                                class="opacity-90 hover:opacity-100">{{ __('Clear') }}</button>
                    </div>
                </div>
            @endif
        @endcan

        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        @can('manage workers')
                            <th class="px-4 py-3 w-10">
                                <input type="checkbox" wire:model.live="selectPage"
                                       class="rounded border-hairline text-accent focus:ring-accent/40" />
                            </th>
                        @endcan
                        <th class="px-6 py-3 text-start font-medium">{{ __('Employee No') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Name') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Profession') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Nationality') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-end font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($workers as $worker)
                        <tr wire:key="worker-{{ $worker->id }}">
                            @can('manage workers')
                                <td class="px-4 py-3">
                                    <input type="checkbox" value="{{ $worker->id }}" wire:model.live="selected"
                                           class="rounded border-hairline text-accent focus:ring-accent/40" />
                                </td>
                            @endcan
                            <td class="px-6 py-3">{{ $worker->employee_no ?? '—' }}</td>
                            <td class="px-6 py-3 font-medium text-ink">{{ $worker->name() }}</td>
                            <td class="px-6 py-3">{{ __($worker->profession) }}</td>
                            <td class="px-6 py-3">{{ __($worker->nationality) ?? '—' }}</td>
                            <td class="px-6 py-3"><x-status-pill :status="$worker->availability" /></td>
                            <td class="px-6 py-3 text-end space-x-2 rtl:space-x-reverse">
                                @can('manage workers')
                                    <button wire:click="edit({{ $worker->id }})"
                                            class="text-accent hover:text-success">{{ __('Edit') }}</button>
                                    <button wire:click="delete({{ $worker->id }})"
                                            wire:confirm="{{ __('Are you sure?') }}"
                                            class="text-danger hover:text-danger">{{ __('Delete') }}</button>
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

        <div>{{ $workers->links() }}</div>
    </div>

    <x-modal name="worker-form" :show="false" focusable maxWidth="2xl">
        <form wire:submit="save" class="p-6 space-y-4">
            <h2 class="text-lg font-semibold text-ink">
                {{ $editingId ? __('Edit') : __('Create') }} — {{ __('Worker') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="employee_no" :value="__('Employee No')" />
                    <x-text-input wire:model="employee_no" id="employee_no" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="w_profession" :value="__('Profession')" />
                    <x-text-input wire:model="profession" id="w_profession" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('profession')" />
                </div>
                <div>
                    <x-input-label for="w_name_en" :value="__('Name') . ' (EN)'" />
                    <x-text-input wire:model="name_en" id="w_name_en" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('name_en')" />
                </div>
                <div>
                    <x-input-label for="w_name_ar" :value="__('Name') . ' (AR)'" />
                    <x-text-input wire:model="name_ar" id="w_name_ar" class="mt-1 block w-full" dir="rtl" />
                    <x-input-error class="mt-2" :messages="$errors->get('name_ar')" />
                </div>
                <div>
                    <x-input-label for="nationality" :value="__('Nationality')" />
                    <x-text-input wire:model="nationality" id="nationality" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="gender" :value="__('Gender')" />
                    <select wire:model="gender" id="gender"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="male">{{ __('Male') }}</option>
                        <option value="female">{{ __('Female') }}</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="iqama_number" :value="__('Iqama')" />
                    <x-text-input wire:model="iqama_number" id="iqama_number" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="passport_number" :value="__('Passport')" />
                    <x-text-input wire:model="passport_number" id="passport_number" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="availability" :value="__('Availability')" />
                    <select wire:model="availability" id="availability"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="available">{{ __('Available') }}</option>
                        <option value="deployed">{{ __('Deployed') }}</option>
                        <option value="on_leave">{{ __('On Leave') }}</option>
                        <option value="exiting">{{ __('Exiting') }}</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="sponsorship_status" :value="__('Sponsorship')" />
                    <select wire:model="sponsorship_status" id="sponsorship_status"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="sponsored">{{ __('Sponsored') }}</option>
                        <option value="transferred_out">{{ __('Transferred Out') }}</option>
                        <option value="exited">{{ __('Exited') }}</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="payment_method" :value="__('Payment Method')" />
                    <select wire:model="payment_method" id="payment_method"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                        <option value="payroll_card">{{ __('Payroll Card') }}</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="bank_name" :value="__('Bank Name')" />
                    <x-text-input wire:model="bank_name" id="bank_name" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="iban" :value="__('IBAN')" />
                    <x-text-input wire:model="iban" id="iban" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="phone" :value="__('Phone')" />
                    <x-text-input wire:model="phone" id="phone" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="qiwa_contract_salary" :value="__('Qiwa Contract Salary')" />
                    <x-text-input wire:model="qiwa_contract_salary" id="qiwa_contract_salary" type="number" step="0.01" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('qiwa_contract_salary')" />
                </div>
                <div>
                    <x-input-label for="basic_salary" :value="__('Basic Salary')" />
                    <x-text-input wire:model="basic_salary" id="basic_salary" type="number" step="0.01" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('basic_salary')" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'worker-form')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-brand-button>{{ __('Save') }}</x-brand-button>
            </div>
        </form>
    </x-modal>

    {{-- Bulk edit modal --}}
    <x-modal name="worker-bulk-form" :show="false" focusable maxWidth="2xl">
        <div class="p-6 space-y-4">
            <div>
                <h2 class="text-lg font-semibold text-ink">
                    {{ __('Bulk Edit') }} — {{ __(':count workers selected', ['count' => count($selected)]) }}
                </h2>
                <p class="text-sm text-ink-faint mt-1">
                    {{ __('Tick a field to change it for every selected worker. Unticked fields are left untouched. Unique fields (employee no, names, iqama, passport, IBAN, phone) are not bulk-editable.') }}
                </p>
            </div>

            @error('bulkApply')
                <div class="rounded-md bg-danger/10 border border-red-200 p-3 text-sm text-danger">{{ $message }}</div>
            @enderror

            @php
                $bulkFields = [
                    'profession' => ['label' => __('Profession'), 'type' => 'text'],
                    'nationality' => ['label' => __('Nationality'), 'type' => 'text'],
                    'gender' => ['label' => __('Gender'), 'type' => 'select', 'options' => ['male' => __('Male'), 'female' => __('Female')]],
                    'availability' => ['label' => __('Availability'), 'type' => 'select', 'options' => ['available' => __('Available'), 'deployed' => __('Deployed'), 'on_leave' => __('On Leave'), 'exiting' => __('Exiting')]],
                    'sponsorship_status' => ['label' => __('Sponsorship'), 'type' => 'select', 'options' => ['sponsored' => __('Sponsored'), 'transferred_out' => __('Transferred Out'), 'exited' => __('Exited')]],
                    'payment_method' => ['label' => __('Payment Method'), 'type' => 'select', 'options' => ['bank_transfer' => __('Bank Transfer'), 'payroll_card' => __('Payroll Card')]],
                    'bank_name' => ['label' => __('Bank Name'), 'type' => 'text'],
                    'notes' => ['label' => __('Notes'), 'type' => 'text'],
                ];
            @endphp

            @if (! $bulkConfirming)
                {{-- Step 1: choose fields & values --}}
                <div class="space-y-3">
                    @foreach ($bulkFields as $field => $meta)
                        <div class="flex items-start gap-3">
                            <label class="flex items-center gap-2 w-48 shrink-0 pt-2">
                                <input type="checkbox" wire:model.live="bulkApply.{{ $field }}"
                                       class="rounded border-hairline text-accent focus:ring-accent/40" />
                                <span class="text-sm font-medium text-ink-muted">{{ $meta['label'] }}</span>
                            </label>
                            <div class="flex-1">
                                @if ($meta['type'] === 'select')
                                    <select wire:model="bulk.{{ $field }}" @disabled(! ($bulkApply[$field] ?? false))
                                            class="block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass disabled:bg-surface-sunken disabled:text-ink-faint">
                                        @foreach ($meta['options'] as $val => $text)
                                            <option value="{{ $val }}">{{ $text }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <x-text-input wire:model="bulk.{{ $field }}"
                                                  :disabled="! ($bulkApply[$field] ?? false)"
                                                  class="block w-full disabled:bg-surface-sunken disabled:text-ink-faint"
                                                  :placeholder="($bulkApply[$field] ?? false) ? '' : __('— unchanged —')" />
                                @endif
                                <x-input-error class="mt-1" :messages="$errors->get('bulk.' . $field)" />
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'worker-bulk-form')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                    <x-brand-button type="button" wire:click="bulkReview">
                        {{ __('Apply to :count', ['count' => count($selected)]) }}
                    </x-brand-button>
                </div>
            @else
                {{-- Step 2: confirm --}}
                <div class="rounded-md bg-warning/10 border border-warning/30 p-4 text-sm text-warning space-y-2">
                    <p class="font-medium">{{ __('Confirm bulk update of :count workers:', ['count' => count($selected)]) }}</p>
                    <ul class="list-disc ps-5 space-y-1">
                        @foreach (array_keys(array_filter($bulkApply)) as $field)
                            <li>
                                <span class="font-medium">{{ $bulkFields[$field]['label'] ?? $field }}</span>
                                → {{ $bulkFields[$field]['options'][$bulk[$field]] ?? ($bulk[$field] === '' ? __('(empty)') : $bulk[$field]) }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <x-secondary-button type="button" wire:click="$set('bulkConfirming', false)">
                        {{ __('Back') }}
                    </x-secondary-button>
                    <x-brand-button type="button" wire:click="bulkSave">
                        {{ __('Confirm & Update') }}
                    </x-brand-button>
                </div>
            @endif
        </div>
    </x-modal>
</div>
