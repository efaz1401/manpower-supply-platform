{{--
    ⌘K command palette — global jump-to + actions.

    Opens with ⌘K / Ctrl+K, or "/" (when not typing in a field). The topbar
    dispatches `open-command-palette`. Every entry is filtered server-side by
    the SAME role checks the routes use, so the palette can never offer a link
    that would 403.
--}}

@props([])

@php
    $u = auth()->user();
    $isBackOffice = $u?->hasAnyRole(['admin', 'manager', 'ops']);
    $isExec = $u?->hasAnyRole(['admin', 'manager']);
    $isClient = $u?->hasRole('client');
    $isWorker = $u?->hasRole('worker');
    $isAdmin = $u?->hasRole('admin');

    $can = fn (string $permission) => $u?->can($permission) ?? false;

    $nav = [];

    $push = function (string $group, string $label, string $url, string $icon, array $keywords = []) use (&$nav) {
        $nav[] = [
            'group' => $group,
            'label' => $label,
            'url' => $url,
            'icon' => $icon,
            'keywords' => implode(' ', $keywords),
        ];
    };

    // --- Navigation -------------------------------------------------------
    if ($isBackOffice) {
        $push('Operations', __('Operations Dashboard'), route('operations.dashboard'), 'operations');
        $push('Operations', __('Workers'), route('workers.index'), 'workers', ['employees', 'labour', 'iqama']);
        $push('Operations', __('Clients'), route('clients.index'), 'clients', ['customers', 'companies']);
        $push('Operations', __('Supply Contracts'), route('contracts.index'), 'contracts');
        $push('Operations', __('Deployments'), route('deployments.index'), 'deployments', ['placement', 'site']);
        $push('Operations', __('Timesheets'), route('timesheets.index'), 'timesheets', ['attendance', 'hours']);
        $push('Operations', __('Payroll'), route('payroll.index'), 'payroll', ['wps', 'salary', 'qiwa']);
        $push('Operations', __('Invoicing'), route('invoices.index'), 'invoices', ['vat', 'zatca', 'billing']);
        $push('Operations', __('Recruitment'), route('recruitment.index'), 'recruitment', ['candidates', 'visa']);
        $push('Operations', __('Compliance Radar'), route('compliance.index'), 'compliance', ['documents', 'expiry', 'alerts']);
    }

    $push('Management', __('Dashboard'), route('dashboard'), 'dashboard');
    if ($isExec) {
        $push('Management', __('Executive Dashboard'), route('executive.dashboard'), 'executive', ['kpi', 'overview']);
    }

    if ($isClient) {
        $push('Client Portal', __('Dashboard'), route('client.dashboard'), 'dashboard');
        $push('Client Portal', __('Workers'), route('client.workers'), 'workers');
        $push('Client Portal', __('Timesheets'), route('client.timesheets'), 'timesheets');
        $push('Client Portal', __('Invoices'), route('client.invoices'), 'invoices');
    }

    if ($isWorker) {
        $push('Worker Portal', __('Dashboard'), route('worker.dashboard'), 'dashboard');
        $push('Worker Portal', __('Documents'), route('worker.documents'), 'documents');
        $push('Worker Portal', __('Payroll'), route('worker.payslips'), 'payroll');
    }

    if ($isAdmin) {
        $push('Administration', __('Users'), route('admin.users'), 'users');
        $push('Administration', __('Activity Log'), route('admin.activity'), 'activity', ['audit', 'history']);
    }

    // --- Actions ----------------------------------------------------------
    $actions = [];

    if ($can('view compliance')) {
        $actions[] = [
            'group' => 'Actions', 'type' => 'navigate',
            'label' => __('Run compliance scan'),
            'url' => route('compliance.index', ['scan' => 1]),
            'icon' => 'shield', 'keywords' => 'scan expiry alerts documents',
        ];
    }
    if ($can('manage workers')) {
        $actions[] = ['group' => 'Actions', 'type' => 'navigate', 'label' => __('Add worker'), 'url' => route('workers.index'), 'icon' => 'plus', 'keywords' => 'new create employee'];
    }
    if ($can('manage clients')) {
        $actions[] = ['group' => 'Actions', 'type' => 'navigate', 'label' => __('Add client'), 'url' => route('clients.index'), 'icon' => 'plus', 'keywords' => 'new create company'];
    }
    if ($can('manage deployments')) {
        $actions[] = ['group' => 'Actions', 'type' => 'navigate', 'label' => __('New deployment'), 'url' => route('deployments.index'), 'icon' => 'plus', 'keywords' => 'create placement'];
    }
    if ($can('manage invoices')) {
        $actions[] = ['group' => 'Actions', 'type' => 'navigate', 'label' => __('New invoice'), 'url' => route('invoices.index'), 'icon' => 'plus', 'keywords' => 'create billing vat'];
    }

    $actions[] = ['group' => 'Actions', 'type' => 'theme', 'label' => __('Toggle theme'), 'url' => '#', 'icon' => 'sun', 'keywords' => 'dark light mode'];
    $actions[] = ['group' => 'Actions', 'type' => 'navigate', 'label' => __('Profile'), 'url' => route('profile'), 'icon' => 'users', 'keywords' => 'account settings password'];

    $items = array_merge($nav, $actions);

    // Resolve each item's icon to inline SVG once, server-side, so the Alpine
    // template never needs a JS icon map. Group headings are translated here so
    // the palette keeps Arabic parity like the rest of the UI.
    $groupLabels = [
        'Operations' => __('Operations'),
        'Management' => __('Management'),
        'Administration' => __('Administration'),
        'Client Portal' => __('Client Portal'),
        'Worker Portal' => __('Worker Portal'),
        'Actions' => __('Actions'),
    ];

    foreach ($items as &$item) {
        $item['svg'] = \Illuminate\Support\Facades\Blade::render(
            '<x-icon :name="$name" class="h-4 w-4" />',
            ['name' => $item['icon']]
        );
        $item['group'] = $groupLabels[$item['group']] ?? $item['group'];
    }
    unset($item);
@endphp

<div x-data="commandPalette({{ Illuminate\Support\Js::from($items) }})"
     x-on:open-command-palette.window="show()"
     x-on:keydown.window="hotkey($event)"
     x-on:keydown.escape.window="hide()"
     x-show="open"
     x-cloak
     x-effect="document.body.classList.toggle('overflow-hidden', open)"
     class="fixed inset-0 z-[60]"
     role="dialog"
     aria-modal="true"
     aria-label="{{ __('Command palette') }}">

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="transition-opacity duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         x-on:click="hide()"
         class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    {{-- Panel --}}
    <div x-show="open"
         x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 translate-y-2 scale-[0.98]" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition duration-100 ease-in" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-[0.98]"
         class="relative mx-auto mt-[10vh] w-[92vw] max-w-xl overflow-hidden glass-panel shadow-glass">

        {{-- Search row --}}
        <div class="flex items-center gap-3 border-b border-hairline px-4">
            <x-icon name="search" class="h-5 w-5 shrink-0 text-ink-faint" />
            <input x-ref="input"
                   x-model="query"
                   x-on:input="active = 0"
                   x-on:keydown.arrow-down.prevent="move(1)"
                   x-on:keydown.arrow-up.prevent="move(-1)"
                   x-on:keydown.enter.prevent="run(filtered[active])"
                   type="text"
                   autocomplete="off"
                   placeholder="{{ __('Search or jump to…') }}"
                   class="w-full border-0 bg-transparent py-4 text-base text-ink placeholder:text-ink-faint focus:ring-0" />
            <kbd class="hidden shrink-0 rounded bg-surface-raised px-1.5 py-0.5 text-[10px] text-ink-faint ring-1 ring-hairline sm:block">ESC</kbd>
        </div>

        {{-- Results --}}
        <div x-ref="list" class="max-h-[52vh] overflow-y-auto p-2">
            <p x-show="!filtered.length" class="px-3 py-8 text-center text-sm text-ink-faint">
                {{ __('No results found.') }}
            </p>

            <template x-for="group in groups" :key="group">
                <div class="mb-1">
                    <p class="px-3 pb-1 pt-2 text-xs uppercase tracking-wide text-ink-faint" x-text="group"></p>

                    <template x-for="item in inGroup(group)" :key="item.label + item.url">
                        <button type="button"
                                x-on:click="run(item)"
                                x-on:mousemove="active = indexOf(item)"
                                :data-active="indexOf(item) === active"
                                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-start transition-colors"
                                :class="indexOf(item) === active
                                    ? 'bg-accent/15 text-ink ring-1 ring-accent/30'
                                    : 'text-ink-muted hover:bg-surface-raised hover:text-ink'">
                            <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-surface-sunken ring-1 ring-hairline"
                                  x-html="item.svg"></span>
                            <span class="flex-1 truncate" x-text="item.label"></span>
                            <span class="shrink-0 text-xs text-ink-faint" x-show="indexOf(item) === active">↵</span>
                        </button>
                    </template>
                </div>
            </template>
        </div>

        {{-- Footer hints --}}
        <div class="flex items-center justify-between border-t border-hairline px-4 py-2 text-[11px] text-ink-faint">
            <span class="flex items-center gap-3">
                <span><kbd class="rounded bg-surface-raised px-1 ring-1 ring-hairline">↑↓</kbd> {{ __('Navigate') }}</span>
                <span><kbd class="rounded bg-surface-raised px-1 ring-1 ring-hairline">↵</kbd> {{ __('Open') }}</span>
            </span>
            <span x-text="filtered.length + ' / ' + items.length"></span>
        </div>
    </div>
</div>
