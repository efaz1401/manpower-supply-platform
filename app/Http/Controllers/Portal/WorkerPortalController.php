<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\PayrollLine;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WorkerPortalController extends Controller
{
    protected function worker(): ?Worker
    {
        return Auth::user()->worker;
    }

    public function dashboard(): View
    {
        $worker = $this->worker()?->load('currentDeployment.client', 'documents');

        return view('portal.worker.dashboard', compact('worker'));
    }

    public function documents(): View
    {
        $documents = $this->worker()?->documents()->orderBy('expiry_date')->get() ?? collect();

        return view('portal.worker.documents', compact('documents'));
    }

    public function payslips(): View
    {
        $payslips = PayrollLine::with('payrollRun')
            ->where('worker_id', $this->worker()?->id)
            ->latest()
            ->get();

        return view('portal.worker.payslips', compact('payslips'));
    }
}
