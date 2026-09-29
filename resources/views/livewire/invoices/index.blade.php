<?php

use App\Models\Client;
use App\Models\Invoice;
use App\Services\InvoiceBuilder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    #[Url]
    public string $statusFilter = '';

    // Generate form
    public $gen_client_id = '';
    public string $gen_period = '';

    public ?int $viewingId = null;

    public function mount(): void
    {
        $this->authorize('manage invoices');
        $this->gen_period = now()->subMonth()->format('Y-m');
    }

    public function generate(): void
    {
        $this->validate([
            'gen_client_id' => ['required', 'exists:clients,id'],
            'gen_period' => ['required', 'date_format:Y-m'],
        ]);

        $client = Client::findOrFail($this->gen_client_id);
        $period = Carbon::createFromFormat('Y-m', $this->gen_period)->startOfMonth();

        $invoice = app(InvoiceBuilder::class)->buildForClient(
            $client,
            $period->copy()->startOfMonth(),
            $period->copy()->endOfMonth(),
        );

        if (! $invoice) {
            $this->addError('gen_client_id', __('This client has no active deployments to bill.'));

            return;
        }

        $this->dispatch('saved');
    }

    public function markPaid(int $id): void
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => 'paid', 'amount_paid' => $invoice->total]);
        $this->dispatch('saved');
    }

    public function view(int $id): void
    {
        $this->viewingId = $id;
        $this->dispatch('open-modal', 'invoice-lines');
    }

    public function with(): array
    {
        return [
            'invoices' => Invoice::query()
                ->with('client')
                ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
                ->latest('issue_date')
                ->paginate(15),
            'clients' => Client::orderBy('name_en')->get(['id', 'name_en', 'name_ar']),
            'viewing' => $this->viewingId ? Invoice::with('lines.worker')->find($this->viewingId) : null,
        ];
    }
}; ?>

<div>
    <div class="p-4 sm:p-6 lg:p-8 space-y-6">
        <h2 class="text-xl font-semibold text-ink">{{ __('Invoices') }}</h2>

        {{-- Generate invoice --}}
        <form wire:submit="generate" class="bg-surface rounded-lg shadow-glass border border-hairline p-5 flex flex-wrap items-end gap-3">
            <div>
                <x-input-label for="gen_client_id" :value="__('Client')" />
                <select wire:model="gen_client_id" id="gen_client_id"
                        class="mt-1 bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                    <option value="">— {{ __('Select') }} —</option>
                    @foreach ($clients as $c)
                        <option value="{{ $c->id }}">{{ $c->name() }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gen_client_id')" />
            </div>
            <div>
                <x-input-label for="gen_period" :value="__('Period') . ' (YYYY-MM)'" />
                <x-text-input wire:model="gen_period" id="gen_period" class="mt-1 w-32" placeholder="2026-06" />
                <x-input-error class="mt-2" :messages="$errors->get('gen_period')" />
            </div>
            <x-brand-button>{{ __('Generate Invoice') }}</x-brand-button>
        </form>

        <div class="flex gap-3">
            <select wire:model.live="statusFilter"
                    class="bg-surface-sunken text-ink border-hairline focus:border-accent focus:ring-accent/40 rounded-md shadow-glass">
                <option value="">{{ __('All') }}</option>
                <option value="draft">{{ __('Draft') }}</option>
                <option value="issued">{{ __('Issued') }}</option>
                <option value="partially_paid">{{ __('Partially Paid') }}</option>
                <option value="paid">{{ __('Paid') }}</option>
                <option value="overdue">{{ __('Overdue') }}</option>
            </select>
        </div>

        <div class="bg-surface rounded-lg shadow-glass border border-hairline overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-surface-sunken/60 text-ink-faint">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Invoice Number') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Client') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Issue Date') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Total') }}</th>
                        <th class="px-6 py-3 text-start font-medium">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-end font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse ($invoices as $invoice)
                        <tr wire:key="invoice-{{ $invoice->id }}">
                            <td class="px-6 py-3 font-medium text-ink">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-3">{{ $invoice->client?->name() }}</td>
                            <td class="px-6 py-3">@greg($invoice->issue_date)</td>
                            <td class="px-6 py-3">@money($invoice->total)</td>
                            <td class="px-6 py-3"><x-status-pill :status="$invoice->status" /></td>
                            <td class="px-6 py-3 text-end space-x-2 rtl:space-x-reverse">
                                <button wire:click="view({{ $invoice->id }})"
                                        class="text-accent hover:text-success">{{ __('View Lines') }}</button>
                                @if (! in_array($invoice->status, ['paid', 'cancelled']))
                                    <button wire:click="markPaid({{ $invoice->id }})"
                                            wire:confirm="{{ __('Mark this invoice as paid?') }}"
                                            class="text-accent hover:text-success font-medium">{{ __('Mark Paid') }}</button>
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

        <div>{{ $invoices->links() }}</div>
    </div>

    {{-- Invoice detail modal --}}
    <x-modal name="invoice-lines" :show="false" maxWidth="2xl">
        <div class="p-6">
            @if ($viewing)
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-ink">{{ $viewing->invoice_number }}</h2>
                        <p class="text-sm text-ink-faint">{{ $viewing->client?->name() }}</p>
                    </div>
                    <x-status-pill :status="$viewing->status" />
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-surface-sunken/60 text-ink-faint">
                            <tr>
                                <th class="px-4 py-2 text-start font-medium">{{ __('Description') }}</th>
                                <th class="px-4 py-2 text-start font-medium">{{ __('Quantity') }}</th>
                                <th class="px-4 py-2 text-start font-medium">{{ __('Unit Price') }}</th>
                                <th class="px-4 py-2 text-end font-medium">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-hairline">
                            @foreach ($viewing->lines as $line)
                                <tr wire:key="iline-{{ $line->id }}">
                                    <td class="px-4 py-2">{{ $line->description }}</td>
                                    <td class="px-4 py-2">{{ $line->quantity }} {{ __($line->unit) }}</td>
                                    <td class="px-4 py-2">@money($line->unit_price)</td>
                                    <td class="px-4 py-2 text-end">@money($line->line_total)</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end">
                    <dl class="text-sm space-y-1 w-64">
                        <div class="flex justify-between">
                            <dt class="text-ink-faint">{{ __('Subtotal') }}</dt>
                            <dd>@money($viewing->subtotal)</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-ink-faint">{{ __('VAT') }} ({{ $viewing->vat_rate }}%)</dt>
                            <dd>@money($viewing->vat_amount)</dd>
                        </div>
                        <div class="flex justify-between font-semibold border-t pt-1">
                            <dt>{{ __('Total') }}</dt>
                            <dd>@money($viewing->total)</dd>
                        </div>
                    </dl>
                </div>

                @if ($viewing->qr_payload)
                    <p class="mt-4 text-xs text-ink-faint break-all">
                        <span class="font-medium">{{ __('ZATCA QR') }}:</span> {{ $viewing->qr_payload }}
                    </p>
                @endif
            @endif

            <div class="flex justify-end mt-4">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal', 'invoice-lines')">
                    {{ __('Close') }}
                </x-secondary-button>
            </div>
        </div>
    </x-modal>
</div>
