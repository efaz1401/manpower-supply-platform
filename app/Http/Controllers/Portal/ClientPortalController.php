<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Deployment;
use App\Models\Invoice;
use App\Models\Timesheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClientPortalController extends Controller
{
    /** The client company linked to the signed-in portal user. */
    protected function clientId(): ?int
    {
        return Auth::user()->client_id;
    }

    public function dashboard(): View
    {
        $clientId = $this->clientId();

        $stats = [
            'active_deployments' => Deployment::where('client_id', $clientId)->where('status', 'active')->count(),
            'pending_timesheets' => Timesheet::whereHas('deployment', fn ($q) => $q->where('client_id', $clientId))
                ->where('status', 'submitted')->count(),
            'outstanding' => Invoice::where('client_id', $clientId)
                ->whereIn('status', ['issued', 'partially_paid', 'overdue'])->sum('total'),
        ];

        return view('portal.client.dashboard', compact('stats'));
    }

    public function workers(): View
    {
        $deployments = Deployment::with('worker')
            ->where('client_id', $this->clientId())
            ->where('status', 'active')
            ->get();

        return view('portal.client.workers', compact('deployments'));
    }

    public function timesheets(): View
    {
        $timesheets = Timesheet::with('worker')
            ->whereHas('deployment', fn ($q) => $q->where('client_id', $this->clientId()))
            ->latest('work_date')
            ->limit(50)
            ->get();

        return view('portal.client.timesheets', compact('timesheets'));
    }

    public function invoices(): View
    {
        $invoices = Invoice::where('client_id', $this->clientId())
            ->latest('issue_date')
            ->get();

        return view('portal.client.invoices', compact('invoices'));
    }
}
