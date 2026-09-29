<?php

use App\Models\Client;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public ?int $editingId = null;

    public string $name = '';
    public string $name_ar = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $role = '';
    public $client_id = '';
    public $worker_id = '';
    public bool $is_active = true;

    public function mount(): void
    {
        $this->authorize('manage users');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'phone' => ['nullable', 'string', 'max:255'],
            'password' => [$this->editingId ? 'nullable' : 'required', 'nullable', 'string', 'min:8'],
            'role' => ['required', 'exists:roles,name'],
            'client_id' => ['nullable', 'exists:clients,id'],
            'worker_id' => ['nullable', 'exists:workers,id'],
            'is_active' => ['boolean'],
        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', 'user-form');
    }

    public function edit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->name_ar = $user->name_ar ?? '';
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->password = '';
        $this->role = $user->roles->first()?->name ?? '';
        $this->client_id = $user->client_id ?? '';
        $this->worker_id = $user->worker_id ?? '';
        $this->is_active = (bool) $user->is_active;
        $this->dispatch('open-modal', 'user-form');
    }

    public function save(): void
    {
        $data = $this->validate();
        $role = $data['role'];
        unset($data['role']);

        $data['client_id'] = $data['client_id'] ?: null;
        $data['worker_id'] = $data['worker_id'] ?: null;

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);
            $user->update($data);
        } else {
            $user = User::create($data);
        }

        $user->syncRoles([$role]);

        $this->dispatch('close-modal', 'user-form');
        $this->dispatch('saved');
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        // Guard against deleting your own account.
        if ($id === auth()->id()) {
            return;
        }
        User::findOrFail($id)->delete();
        $this->dispatch('saved');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'name_ar', 'email', 'phone', 'password', 'role', 'client_id', 'worker_id']);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function with(): array
    {
        return [
            'users' => User::query()
                ->with('roles')
                ->when($this->search, fn ($q) => $q->where(fn ($w) => $w
                    ->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")))
                ->latest()
                ->paginate(15),
            'roles' => Role::orderBy('name')->pluck('name'),
            'clients' => Client::orderBy('name_en')->get(['id', 'name_en', 'name_ar']),
            'workers' => Worker::orderBy('name_en')->get(['id', 'name_en', 'name_ar']),
        ];
    }
}; ?>

<div>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-ink">{{ __('Users') }}</h2>
            <x-brand-button type="button" wire:click="create">{{ __('New User') }}</x-brand-button>
        </div>

        <x-text-input wire:model.live.debounce.300ms="search" type="search"
                      class="w-full sm:max-w-xs" :placeholder="__('Search')" />

        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Name') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Email') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Roles') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-end font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td class="px-6 py-3 font-medium text-ink">{{ $user->displayName() }}</td>
                            <td class="px-6 py-3">{{ $user->email }}</td>
                            <td class="px-6 py-3">
                                @foreach ($user->roles as $r)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-surface-sunken text-ink-muted">{{ __(ucfirst($r->name)) }}</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-3"><x-status-pill :status="$user->is_active ? 'active' : 'inactive'" /></td>
                            <td class="px-6 py-3 text-end space-x-2 rtl:space-x-reverse">
                                <button wire:click="edit({{ $user->id }})"
                                        class="text-accent hover:text-success">{{ __('Edit') }}</button>
                                @if ($user->id !== auth()->id())
                                    <button wire:click="delete({{ $user->id }})"
                                            wire:confirm="{{ __('Are you sure?') }}"
                                            class="text-danger hover:text-danger">{{ __('Delete') }}</button>
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

        <div>{{ $users->links() }}</div>
    </div>

    <x-modal name="user-form" :show="false" focusable maxWidth="lg">
        <form wire:submit="save" class="p-6 space-y-4">
            <h2 class="text-lg font-semibold text-ink">
                {{ $editingId ? __('Edit') : __('Create') }} — {{ __('Users') }}
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="u_name" :value="__('Name')" />
                    <x-text-input wire:model="name" id="u_name" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="u_name_ar" :value="__('Name') . ' (AR)'" />
                    <x-text-input wire:model="name_ar" id="u_name_ar" class="mt-1 block w-full" dir="rtl" />
                </div>
                <div>
                    <x-input-label for="u_email" :value="__('Email')" />
                    <x-text-input wire:model="email" id="u_email" type="email" class="mt-1 block w-full" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>
                <div>
                    <x-input-label for="u_phone" :value="__('Phone')" />
                    <x-text-input wire:model="phone" id="u_phone" class="mt-1 block w-full" />
                </div>
                <div>
                    <x-input-label for="u_password" :value="__('Password')" />
                    <x-text-input wire:model="password" id="u_password" type="password" class="mt-1 block w-full"
                                  :placeholder="$editingId ? __('Leave blank to keep') : ''" />
                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                </div>
                <div>
                    <x-input-label for="u_role" :value="__('Roles')" />
                    <select wire:model.live="role" id="u_role"
                            class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                        <option value="">— {{ __('Select') }} —</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r }}">{{ __(ucfirst($r)) }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('role')" />
                </div>
                @if ($role === 'client')
                    <div class="sm:col-span-2">
                        <x-input-label for="u_client" :value="__('Client')" />
                        <select wire:model="client_id" id="u_client"
                                class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                            <option value="">— {{ __('Select') }} —</option>
                            @foreach ($clients as $c)
                                <option value="{{ $c->id }}">{{ $c->name() }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                @if ($role === 'worker')
                    <div class="sm:col-span-2">
                        <x-input-label for="u_worker" :value="__('Worker')" />
                        <select wire:model="worker_id" id="u_worker"
                                class="mt-1 block w-full bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                            <option value="">— {{ __('Select') }} —</option>
                            @foreach ($workers as $w)
                                <option value="{{ $w->id }}">{{ $w->name() }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" wire:model="is_active" id="u_active"
                           class="rounded border-hairline text-accent focus:ring-accent/40" />
                    <x-input-label for="u_active" :value="__('Active')" class="!mt-0" />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'user-form')">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-brand-button>{{ __('Save') }}</x-brand-button>
            </div>
        </form>
    </x-modal>
</div>
