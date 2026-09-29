<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Deployment;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Timesheet;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Send each user to the dashboard appropriate for their role. */
    public function redirect()
    {
        return redirect()->route(Auth::user()->homeRoute());
    }

    /** Executive / management KPI dashboard. */
    public function executive(): View
    {
        $total = Worker::count();
        $deployed = Worker::where('availability', 'deployed')->count();

        $stats = [
            'total_workers' => $total,
            'available' => Worker::where('availability', 'available')->count(),
            'deployed' => $deployed,
            'on_leave' => Worker::where('availability', 'on_leave')->count(),
            'utilization' => $total > 0 ? round($deployed / $total * 100) : 0,
            'active_clients' => Client::where('status', 'active')->count(),
            'active_deployments' => Deployment::where('status', 'active')->count(),
            'expiring_docs' => Document::whereIn('status', ['expiring', 'expired'])->count(),
            'outstanding' => Invoice::whereIn('status', ['issued', 'partially_paid', 'overdue'])
                ->sum('total'),
        ];

        return view('dashboards.executive', compact('stats'));
    }

    /** Back-office operations dashboard. */
    public function operations(): View
    {
        $stats = [
            'available' => Worker::where('availability', 'available')->count(),
            'pending_deployments' => Deployment::where('status', 'pending')->count(),
            'pending_timesheets' => Timesheet::whereIn('status', ['submitted', 'client_approved'])->count(),
            'expiring_docs' => Document::where('status', 'expiring')->count(),
            'expired_docs' => Document::where('status', 'expired')->count(),
        ];

        $expiring = Document::with('documentable')
            ->whereNotNull('expiry_date')
            ->whereIn('status', ['expiring', 'expired'])
            ->orderBy('expiry_date')
            ->limit(10)
            ->get();

        return view('dashboards.operations', compact('stats', 'expiring'));
    }
}
