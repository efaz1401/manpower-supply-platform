@php
    $user = auth()->user();
    // Determine which navigation groups this user can see.
    $isBackOffice = $user?->hasAnyRole(['admin', 'manager', 'ops']);
    $isExec = $user?->hasAnyRole(['admin', 'manager']);
    $isClient = $user?->hasRole('client');
    $isWorker = $user?->hasRole('worker');

    $navBase = 'group relative flex items-center gap-3 px-3 py-2 rounded-xl text-sm transition-colors duration-150';
    $navActive = fn ($pattern) => request()->routeIs($pattern)
        ? 'bg-accent/15 text-ink ring-1 ring-accent/30'
        : 'text-ink-muted hover:bg-surface-raised hover:text-ink';
    // Small active indicator bar (mirrors in RTL via start-0).
    $bar = fn ($pattern) => request()->routeIs($pattern)
        ? '<span class="absolute start-0 inset-y-1 w-0.5 rounded-full bg-accent"></span>' : '';
@endphp

{{-- Mobile backdrop --}}
<div x-data x-show="$store.sidebar.open" x-cloak
     x-on:click="$store.sidebar.close()"
     x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-30 bg-black/60 backdrop-blur-sm md:hidden" aria-hidden="true"></div>

<aside x-data
       x-on:keydown.escape.window="$store.sidebar.close()"
       :class="$store.sidebar.open ? 'translate-x-0' : (document.documentElement.dir === 'rtl' ? 'translate-x-full' : '-translate-x-full')"
       class="fixed inset-y-0 start-0 z-40 w-64 shrink-0 glass border-y-0 border-s-0 flex flex-col transition-transform duration-300 ease-[cubic-bezier(0.22,1,0.36,1)] md:static md:translate-x-0 md:z-20">
    <div class="h-16 flex items-center gap-2.5 px-4 border-b border-hairline">
        <x-application-logo class="h-8 w-8 text-accent" :animate="true" />
        <span class="font-semibold leading-tight text-sm text-ink">{{ __('ASMC Manpower') }}</span>
        <button type="button" x-on:click="$store.sidebar.close()" class="ms-auto md:hidden text-ink-muted hover:text-ink" aria-label="{{ __('Close') }}">
            <x-icon name="chevron" class="h-5 w-5 rtl:rotate-180" />
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1 text-sm" x-on:click="if ($event.target.closest('a')) $store.sidebar.close()">
        <a href="{{ route('dashboard') }}" class="{{ $navBase }} {{ $navActive('dashboard') }}">
            {!! $bar('dashboard') !!}
            <x-icon name="dashboard" class="h-5 w-5" /><span>{{ __('Dashboard') }}</span>
        </a>

        @if ($isExec)
            <a href="{{ route('executive.dashboard') }}" class="{{ $navBase }} {{ $navActive('executive.*') }}">
                {!! $bar('executive.*') !!}
                <x-icon name="executive" class="h-5 w-5" /><span>{{ __('Executive Dashboard') }}</span>
            </a>
        @endif

        @if ($isBackOffice)
            <p class="px-3 pt-4 pb-1 text-xs uppercase tracking-wide text-ink-faint">{{ __('Operations') }}</p>
            <a href="{{ route('operations.dashboard') }}" class="{{ $navBase }} {{ $navActive('operations.*') }}">{!! $bar('operations.*') !!}<x-icon name="operations" class="h-5 w-5" /><span>{{ __('Operations Dashboard') }}</span></a>
            <a href="{{ route('workers.index') }}" class="{{ $navBase }} {{ $navActive('workers.*') }}">{!! $bar('workers.*') !!}<x-icon name="workers" class="h-5 w-5" /><span>{{ __('Workers') }}</span></a>
            <a href="{{ route('clients.index') }}" class="{{ $navBase }} {{ $navActive('clients.*') }}">{!! $bar('clients.*') !!}<x-icon name="clients" class="h-5 w-5" /><span>{{ __('Clients') }}</span></a>
            <a href="{{ route('contracts.index') }}" class="{{ $navBase }} {{ $navActive('contracts.*') }}">{!! $bar('contracts.*') !!}<x-icon name="contracts" class="h-5 w-5" /><span>{{ __('Supply Contracts') }}</span></a>
            <a href="{{ route('deployments.index') }}" class="{{ $navBase }} {{ $navActive('deployments.*') }}">{!! $bar('deployments.*') !!}<x-icon name="deployments" class="h-5 w-5" /><span>{{ __('Deployments') }}</span></a>
            <a href="{{ route('timesheets.index') }}" class="{{ $navBase }} {{ $navActive('timesheets.*') }}">{!! $bar('timesheets.*') !!}<x-icon name="timesheets" class="h-5 w-5" /><span>{{ __('Timesheets') }}</span></a>
            <a href="{{ route('payroll.index') }}" class="{{ $navBase }} {{ $navActive('payroll.*') }}">{!! $bar('payroll.*') !!}<x-icon name="payroll" class="h-5 w-5" /><span>{{ __('Payroll') }}</span></a>
            <a href="{{ route('invoices.index') }}" class="{{ $navBase }} {{ $navActive('invoices.*') }}">{!! $bar('invoices.*') !!}<x-icon name="invoices" class="h-5 w-5" /><span>{{ __('Invoicing') }}</span></a>
            <a href="{{ route('recruitment.index') }}" class="{{ $navBase }} {{ $navActive('recruitment.*') }}">{!! $bar('recruitment.*') !!}<x-icon name="recruitment" class="h-5 w-5" /><span>{{ __('Recruitment') }}</span></a>
            <a href="{{ route('compliance.index') }}" class="{{ $navBase }} {{ $navActive('compliance.*') }}">{!! $bar('compliance.*') !!}<x-icon name="compliance" class="h-5 w-5" /><span>{{ __('Compliance Radar') }}</span></a>
        @endif

        @if ($isClient)
            <p class="px-3 pt-4 pb-1 text-xs uppercase tracking-wide text-ink-faint">{{ __('Client Portal') }}</p>
            <a href="{{ route('client.dashboard') }}" class="{{ $navBase }} {{ $navActive('client.dashboard') }}">{!! $bar('client.dashboard') !!}<x-icon name="dashboard" class="h-5 w-5" /><span>{{ __('Dashboard') }}</span></a>
            <a href="{{ route('client.workers') }}" class="{{ $navBase }} {{ $navActive('client.workers') }}">{!! $bar('client.workers') !!}<x-icon name="workers" class="h-5 w-5" /><span>{{ __('Workers') }}</span></a>
            <a href="{{ route('client.timesheets') }}" class="{{ $navBase }} {{ $navActive('client.timesheets') }}">{!! $bar('client.timesheets') !!}<x-icon name="timesheets" class="h-5 w-5" /><span>{{ __('Timesheets') }}</span></a>
            <a href="{{ route('client.invoices') }}" class="{{ $navBase }} {{ $navActive('client.invoices') }}">{!! $bar('client.invoices') !!}<x-icon name="invoices" class="h-5 w-5" /><span>{{ __('Invoices') }}</span></a>
        @endif

        @if ($isWorker)
            <p class="px-3 pt-4 pb-1 text-xs uppercase tracking-wide text-ink-faint">{{ __('Worker Portal') }}</p>
            <a href="{{ route('worker.dashboard') }}" class="{{ $navBase }} {{ $navActive('worker.dashboard') }}">{!! $bar('worker.dashboard') !!}<x-icon name="dashboard" class="h-5 w-5" /><span>{{ __('Dashboard') }}</span></a>
            <a href="{{ route('worker.documents') }}" class="{{ $navBase }} {{ $navActive('worker.documents') }}">{!! $bar('worker.documents') !!}<x-icon name="documents" class="h-5 w-5" /><span>{{ __('Documents') }}</span></a>
            <a href="{{ route('worker.payslips') }}" class="{{ $navBase }} {{ $navActive('worker.payslips') }}">{!! $bar('worker.payslips') !!}<x-icon name="payroll" class="h-5 w-5" /><span>{{ __('Payroll') }}</span></a>
        @endif

        @if ($user?->hasRole('admin'))
            <p class="px-3 pt-4 pb-1 text-xs uppercase tracking-wide text-ink-faint">{{ __('Administration') }}</p>
            <a href="{{ route('admin.users') }}" class="{{ $navBase }} {{ $navActive('admin.users') }}">{!! $bar('admin.users') !!}<x-icon name="users" class="h-5 w-5" /><span>{{ __('Users') }}</span></a>
            <a href="{{ route('admin.activity') }}" class="{{ $navBase }} {{ $navActive('admin.activity') }}">{!! $bar('admin.activity') !!}<x-icon name="activity" class="h-5 w-5" /><span>{{ __('Activity Log') }}</span></a>
        @endif
    </nav>

    {{-- Mobile-only footer: language switcher + theme (desktop has these in the topbar) --}}
    <div class="md:hidden border-t border-hairline p-3 space-y-3">
        <div class="flex items-center rounded-xl ring-1 ring-hairline overflow-hidden text-sm">
            @foreach (config('app.supported_locales') as $code => $meta)
                <a href="{{ route('locale.switch', $code) }}"
                   class="flex-1 text-center px-3 py-2 transition-colors {{ app()->getLocale() === $code ? 'bg-accent text-accent-contrast' : 'text-ink-muted hover:bg-surface-raised' }}">
                    {{ $meta['name'] }}
                </a>
            @endforeach
        </div>
        <div class="flex items-center justify-between">
            <a href="{{ route('profile') }}" class="text-sm text-ink-muted hover:text-ink">{{ __('Profile') }}</a>
            <button type="button" x-data x-on:click="$store.theme.toggle()"
                    class="inline-flex items-center gap-2 rounded-xl ring-1 ring-hairline px-3 py-1.5 text-xs text-ink-muted hover:text-ink transition-colors">
                <x-icon name="sun" class="h-4 w-4" x-show="$store.theme.current === 'dark'" />
                <x-icon name="moon" class="h-4 w-4" x-show="$store.theme.current === 'light'" x-cloak />
                <span x-text="$store.theme.current === 'dark' ? '{{ __('Light mode') }}' : '{{ __('Dark mode') }}'"></span>
            </button>
        </div>
    </div>
</aside>
