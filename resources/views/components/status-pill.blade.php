@props(['status' => ''])

@php
    // Maps every status value used across the domain (worker availability,
    // document/permit/deployment/timesheet/invoice/payroll lifecycles) to a tone.
    $map = [
        // green / healthy
        'valid' => 'green', 'active' => 'green', 'available' => 'green', 'paid' => 'green',
        'verified' => 'green', 'disbursed' => 'green', 'completed' => 'green', 'arrived' => 'green',
        'onboarded' => 'green', 'closed' => 'green',
        // amber / attention
        'expiring' => 'amber', 'pending' => 'amber', 'submitted' => 'amber', 'draft' => 'amber',
        'on_leave' => 'amber', 'issued' => 'amber', 'partially_paid' => 'amber', 'calculated' => 'amber',
        'permit_issued' => 'amber', 'client_approved' => 'amber', 'offered' => 'amber', 'visa' => 'amber',
        'medical' => 'amber', 'travel' => 'amber', 'contract' => 'amber', 'approved' => 'amber',
        // red / critical
        'expired' => 'red', 'missing' => 'red', 'overdue' => 'red', 'rejected' => 'red',
        'failed' => 'red', 'suspended' => 'red', 'cancelled' => 'red', 'terminated' => 'red',
        'exiting' => 'red', 'exited' => 'red',
        // neutral
        'inactive' => 'gray', 'ended' => 'gray', 'sourced' => 'gray', 'deployed' => 'blue',
        'transferred_out' => 'gray',
    ];

    $tones = [
        'green' => 'bg-success/15 text-success ring-1 ring-success/25',
        'amber' => 'bg-warning/15 text-warning ring-1 ring-warning/25',
        'red'   => 'bg-danger/15 text-danger ring-1 ring-danger/30',
        'blue'  => 'bg-info/15 text-info ring-1 ring-info/25',
        'gray'  => 'bg-ink-faint/15 text-ink-muted ring-1 ring-ink-faint/20',
    ];

    $dotTones = [
        'green' => 'bg-success', 'amber' => 'bg-warning', 'red' => 'bg-danger',
        'blue' => 'bg-info', 'gray' => 'bg-ink-faint',
    ];

    $key = $map[$status] ?? 'gray';
    $tone = $tones[$key];
    $dot = $dotTones[$key];
    // Critical states gently pulse to draw the eye.
    $critical = $key === 'red';
    $label = __(ucwords(str_replace('_', ' ', (string) $status)));
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {$tone}"]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $dot }} {{ $critical ? 'animate-pulse-ring' : '' }}"></span>
    {{ $label }}
</span>
