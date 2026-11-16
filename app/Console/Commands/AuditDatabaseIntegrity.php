<?php

namespace App\Console\Commands;

use App\Services\DatabaseIntegrity;
use Illuminate\Console\Command;

class AuditDatabaseIntegrity extends Command
{
    protected $signature = 'db:audit-integrity';

    protected $description = 'Read-only audit of orphan references, mismatched bookings, comment targets and parent cycles.';

    public function handle(DatabaseIntegrity $integrity): int
    {
        $problems = $integrity->problems();
        foreach ($problems as $problem) {
            $this->error($problem);
        }
        if ($problems) {
            $this->error('No data was changed. Resolve these records before migrating.');

            return self::FAILURE;
        }
        $this->info('No relationship integrity problems found.');

        return self::SUCCESS;
    }
}
