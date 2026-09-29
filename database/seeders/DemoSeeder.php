<?php

namespace Database\Seeders;

use App\Models\AjeerPermit;
use App\Models\AlertRule;
use App\Models\Client;
use App\Models\Deployment;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\PayrollLine;
use App\Models\PayrollRun;
use App\Models\RateCard;
use App\Models\RecruitmentCandidate;
use App\Models\SupplyContract;
use App\Models\Timesheet;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedStaffUsers();
        $clients = $this->seedClients();
        $workers = $this->seedWorkers();
        $this->seedDocuments($workers);
        $this->seedDeployments($workers, $clients);
        $this->seedPortalUsers($clients, $workers);
        $this->seedTimesheets($workers);
        $this->seedPayroll($workers);
        $this->seedInvoices($clients);
        $this->seedRecruitment();
        $this->seedAlertRules();
    }

    private function make(string $name, string $nameAr, string $email, string $role, array $extra = []): User
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            array_merge([
                'name' => $name,
                'name_ar' => $nameAr,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'locale' => 'ar',
            ], $extra),
        );
        $user->syncRoles([$role]);

        return $user;
    }

    private function seedStaffUsers(): void
    {
        $this->make('System Administrator', 'مدير النظام', 'admin@asmc.com.sa', 'admin');
        $this->make('Operations Manager', 'مدير العمليات', 'manager@asmc.com.sa', 'manager');
        $this->make('Operations Officer', 'موظف العمليات', 'ops@asmc.com.sa', 'ops');
    }

    /** @return array<int, Client> */
    private function seedClients(): array
    {
        $data = [
            ['Al Fanar Construction', 'الفنار للإنشاءات', 'CR-1010111111', '7-1234567', '311111111100003'],
            ['Rawabi Facilities', 'روابي للمرافق', 'CR-2020222222', '7-2345678', '322222222200003'],
            ['Najd Logistics', 'نجد للخدمات اللوجستية', 'CR-3030333333', '7-3456789', '333333333300003'],
        ];

        $clients = [];
        foreach ($data as [$en, $ar, $cr, $est, $vat]) {
            $client = Client::updateOrCreate(
                ['cr_number' => $cr],
                [
                    'name_en' => $en, 'name_ar' => $ar, 'establishment_id' => $est,
                    'vat_number' => $vat, 'contact_person' => 'Contact', 'contact_email' => 'ops@example.com',
                    'contact_phone' => '+9665000000', 'city' => 'Riyadh', 'payment_terms_days' => 30,
                    'status' => 'active',
                ],
            );

            SupplyContract::updateOrCreate(
                ['client_id' => $client->id, 'reference' => 'SC-'.$client->id],
                [
                    'title_en' => 'Master Supply Agreement', 'title_ar' => 'اتفاقية توريد رئيسية',
                    'professions' => ['Mason', 'Electrician', 'Driver', 'Cleaner'],
                    'headcount_quota' => 20, 'start_date' => now()->subMonths(6),
                    'end_date' => now()->addMonths(6), 'status' => 'active',
                ],
            );

            foreach (['Mason' => 3500, 'Electrician' => 4200, 'Driver' => 3000, 'Cleaner' => 2500] as $prof => $rate) {
                RateCard::updateOrCreate(
                    ['client_id' => $client->id, 'profession' => $prof],
                    ['monthly_rate' => $rate, 'hourly_rate' => round($rate / 208, 2), 'overtime_rate' => round($rate / 208 * 1.5, 2)],
                );
            }

            $clients[] = $client;
        }

        return $clients;
    }

    /** @return array<int, Worker> */
    private function seedWorkers(): array
    {
        $rows = [
            ['Mohammed Aslam', 'محمد أسلم', 'Pakistani', 'Mason', 'deployed'],
            ['Rahim Uddin', 'رحيم الدين', 'Bangladeshi', 'Electrician', 'deployed'],
            ['Suresh Kumar', 'سوريش كومار', 'Indian', 'Driver', 'deployed'],
            ['Arjun Prasad', 'أرجون براساد', 'Nepali', 'Cleaner', 'available'],
            ['Kamal Hasan', 'كمال حسن', 'Bangladeshi', 'Mason', 'available'],
            ['Imran Khan', 'عمران خان', 'Pakistani', 'Electrician', 'deployed'],
            ['Ravi Chandra', 'رافي تشاندرا', 'Indian', 'Driver', 'on_leave'],
            ['Bishnu Thapa', 'بيشنو ثابا', 'Nepali', 'Cleaner', 'deployed'],
            ['Anwar Ali', 'أنور علي', 'Bangladeshi', 'Mason', 'available'],
            ['Vijay Singh', 'فيجاي سينغ', 'Indian', 'Electrician', 'deployed'],
            ['Hassan Raza', 'حسن رضا', 'Pakistani', 'Driver', 'available'],
            ['Deepak Rai', 'ديباك راي', 'Nepali', 'Cleaner', 'exiting'],
        ];

        $salaries = ['Mason' => 3000, 'Electrician' => 3600, 'Driver' => 2600, 'Cleaner' => 2200];

        $workers = [];
        foreach ($rows as $i => [$en, $ar, $nat, $prof, $avail]) {
            $workers[] = Worker::updateOrCreate(
                ['employee_no' => 'EMP-'.str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                [
                    'name_en' => $en, 'name_ar' => $ar, 'nationality' => $nat, 'profession' => $prof,
                    'gender' => 'male', 'date_of_birth' => now()->subYears(30 + $i),
                    'availability' => $avail, 'sponsorship_status' => 'sponsored',
                    'payment_method' => 'bank_transfer', 'bank_name' => 'Al Rajhi Bank',
                    'iban' => 'SA'.str_pad((string) (1000000000000000 + $i), 22, '0'),
                    'qiwa_contract_salary' => $salaries[$prof], 'basic_salary' => $salaries[$prof],
                    'phone' => '+96650'.str_pad((string) $i, 7, '0'),
                    'iqama_number' => '2'.str_pad((string) ($i + 1), 9, '0'),
                ],
            );
        }

        return $workers;
    }

    /** Documents with a spread of expiry dates to exercise compliance statuses. */
    private function seedDocuments(array $workers): void
    {
        // days-from-now => resulting status
        $spread = [
            [400, 'valid'], [200, 'valid'], [80, 'expiring'], [25, 'expiring'],
            [10, 'expiring'], [-5, 'expired'],
        ];

        foreach ($workers as $i => $worker) {
            [$iqamaDays, $iqamaStatus] = $spread[$i % count($spread)];
            [$ppDays, $ppStatus] = $spread[($i + 2) % count($spread)];

            Document::updateOrCreate(
                ['documentable_type' => Worker::class, 'documentable_id' => $worker->id, 'type' => 'iqama'],
                [
                    'number' => $worker->iqama_number, 'authority' => 'MOI',
                    'issue_date' => now()->subYears(1), 'expiry_date' => now()->addDays($iqamaDays),
                    'status' => $iqamaStatus,
                ],
            );

            Document::updateOrCreate(
                ['documentable_type' => Worker::class, 'documentable_id' => $worker->id, 'type' => 'passport'],
                [
                    'number' => 'P'.str_pad((string) $worker->id, 7, '0'), 'authority' => 'Embassy',
                    'issue_date' => now()->subYears(3), 'expiry_date' => now()->addDays($ppDays),
                    'status' => $ppStatus,
                ],
            );
        }
    }

    private function seedDeployments(array $workers, array $clients): void
    {
        foreach ($workers as $i => $worker) {
            if ($worker->availability !== 'deployed') {
                continue;
            }

            $client = $clients[$i % count($clients)];

            $permit = AjeerPermit::updateOrCreate(
                ['worker_id' => $worker->id, 'client_id' => $client->id, 'profession' => $worker->profession],
                [
                    'permit_number' => 'AJ-'.$worker->id.$client->id,
                    'issue_date' => now()->subMonths(3),
                    'expiry_date' => now()->addMonths(9),
                    'status' => 'active',
                ],
            );

            $rate = RateCard::where('client_id', $client->id)->where('profession', $worker->profession)->value('monthly_rate') ?? 3000;

            Deployment::updateOrCreate(
                ['worker_id' => $worker->id, 'client_id' => $client->id, 'status' => 'active'],
                [
                    'ajeer_permit_id' => $permit->id, 'profession' => $worker->profession,
                    'site' => 'Site '.chr(65 + ($i % 3)), 'start_date' => now()->subMonths(2),
                    'billing_rate' => $rate, 'billing_cycle' => 'monthly',
                ],
            );
        }
    }

    private function seedPortalUsers(array $clients, array $workers): void
    {
        $this->make(
            'Al Fanar Portal', 'بوابة الفنار', 'client@asmc.com.sa', 'client',
            ['client_id' => $clients[0]->id],
        );

        $this->make(
            $workers[0]->name_en, $workers[0]->name_ar, 'worker@asmc.com.sa', 'worker',
            ['worker_id' => $workers[0]->id],
        );
    }

    private function seedTimesheets(array $workers): void
    {
        foreach ($workers as $worker) {
            $deployment = Deployment::where('worker_id', $worker->id)->where('status', 'active')->first();
            if (! $deployment) {
                continue;
            }

            for ($d = 1; $d <= 5; $d++) {
                $date = now()->subDays($d);
                Timesheet::updateOrCreate(
                    ['worker_id' => $worker->id, 'work_date' => $date->toDateString()],
                    [
                        'deployment_id' => $deployment->id,
                        'clock_in' => $date->copy()->setTime(8, 0),
                        'clock_out' => $date->copy()->setTime(17, 0),
                        'hours' => 8, 'overtime_hours' => $d === 1 ? 2 : 0,
                        'status' => $d <= 2 ? 'submitted' : 'verified',
                    ],
                );
            }
        }
    }

    private function seedPayroll(array $workers): void
    {
        $period = now()->subMonth();
        $run = PayrollRun::updateOrCreate(
            ['period_year' => $period->year, 'period_month' => $period->month],
            [
                'reference' => 'PR-'.$period->format('Y-m'),
                'period_start' => $period->copy()->startOfMonth(),
                'period_end' => $period->copy()->endOfMonth(),
                'wps_deadline' => $period->copy()->endOfMonth()->addDays(10),
                'status' => 'calculated',
            ],
        );

        foreach ($workers as $i => $worker) {
            // Introduce a couple of Qiwa salary mismatches for demonstration.
            $basic = $i % 5 === 0 ? $worker->basic_salary - 300 : $worker->basic_salary;
            $overtime = $i % 3 === 0 ? 250 : 0;
            $net = $basic + $overtime;

            PayrollLine::updateOrCreate(
                ['payroll_run_id' => $run->id, 'worker_id' => $worker->id],
                [
                    'basic_salary' => $basic, 'overtime_amount' => $overtime, 'deductions' => 0,
                    'net_pay' => $net, 'qiwa_contract_salary' => $worker->qiwa_contract_salary,
                    'salary_mismatch' => (float) $basic !== (float) $worker->qiwa_contract_salary,
                    'payment_method' => 'bank_transfer', 'iban' => $worker->iban, 'status' => 'pending',
                ],
            );
        }
    }

    private function seedInvoices(array $clients): void
    {
        foreach ($clients as $n => $client) {
            $deployments = Deployment::where('client_id', $client->id)->where('status', 'active')->get();
            if ($deployments->isEmpty()) {
                continue;
            }

            $subtotal = 0;
            $invoice = Invoice::updateOrCreate(
                ['invoice_number' => 'INV-'.now()->format('Ym').'-'.str_pad((string) ($n + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'client_id' => $client->id, 'issue_date' => now()->startOfMonth(),
                    'due_date' => now()->startOfMonth()->addDays($client->payment_terms_days),
                    'period_start' => now()->subMonth()->startOfMonth(),
                    'period_end' => now()->subMonth()->endOfMonth(),
                    'vat_rate' => 15, 'currency' => 'SAR', 'status' => 'issued',
                    'seller_vat_number' => '300000000000003', 'buyer_vat_number' => $client->vat_number,
                ],
            );
            $invoice->lines()->delete();

            foreach ($deployments as $d) {
                $subtotal += $d->billing_rate;
                InvoiceLine::create([
                    'invoice_id' => $invoice->id, 'worker_id' => $d->worker_id, 'deployment_id' => $d->id,
                    'description' => $d->profession.' — '.optional($d->worker)->name_en,
                    'quantity' => 1, 'unit' => 'month', 'unit_price' => $d->billing_rate,
                    'line_total' => $d->billing_rate,
                ]);
            }

            $vat = round($subtotal * 0.15, 2);
            $invoice->update([
                'subtotal' => $subtotal, 'vat_amount' => $vat, 'total' => $subtotal + $vat,
            ]);
        }
    }

    private function seedRecruitment(): void
    {
        $rows = [
            ['Faisal Noor', 'فيصل نور', 'Pakistani', 'Mason', 'sourced'],
            ['Gopal Das', 'جوبال داس', 'Indian', 'Driver', 'visa'],
            ['Sanjay Lama', 'سانجاي لاما', 'Nepali', 'Cleaner', 'medical'],
            ['Jamal Uddin', 'جمال الدين', 'Bangladeshi', 'Electrician', 'arrived'],
        ];

        foreach ($rows as $i => [$en, $ar, $nat, $prof, $stage]) {
            RecruitmentCandidate::updateOrCreate(
                ['name_en' => $en],
                ['name_ar' => $ar, 'nationality' => $nat, 'profession' => $prof, 'stage' => $stage],
            );
        }
    }

    private function seedAlertRules(): void
    {
        $types = ['iqama', 'passport', 'work_permit', 'ajeer_permit', 'medical', 'insurance', 'license', 'contract'];
        foreach ($types as $type) {
            AlertRule::updateOrCreate(
                ['document_type' => $type],
                ['thresholds' => [90, 60, 30, 14, 7, 0], 'email_enabled' => false, 'is_active' => true],
            );
        }
    }
}
