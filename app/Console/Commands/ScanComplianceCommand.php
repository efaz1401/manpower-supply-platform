<?php

namespace App\Console\Commands;

use App\Services\ComplianceScanner;
use Illuminate\Console\Command;

class ScanComplianceCommand extends Command
{
    protected $signature = 'compliance:scan';

    protected $description = 'Scan documents and permits for expiry, update statuses, and raise compliance alerts';

    public function handle(ComplianceScanner $scanner): int
    {
        $this->info('Running compliance scan…');

        $result = $scanner->scan();

        $this->table(
            ['Documents scanned', 'Alerts created', 'Alerts resolved'],
            [[$result['documents'], $result['alerts_created'], $result['alerts_resolved']]],
        );

        return self::SUCCESS;
    }
}
