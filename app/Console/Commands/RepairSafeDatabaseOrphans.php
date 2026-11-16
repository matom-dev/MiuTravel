<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RepairSafeDatabaseOrphans extends Command
{
    protected $signature = 'db:repair-safe-orphans {--apply : Archive and repair the supported orphan records}';

    protected $description = 'Archive unreferenced orphan schedules and preserve missing audit actors; dry-run by default.';

    public function handle(): int
    {
        if ($this->option('apply') && ! Schema::hasTable('database_integrity_archives')) {
            $this->error('Run the database_integrity_archives migration first.');

            return self::FAILURE;
        }
        $counts = DB::transaction(function () {
            $counts = ['schedules' => 0, 'actors' => 0, 'blocked' => 0];
            $schedules = DB::table('tour_schedules')->whereNotExists(fn ($q) => $q->selectRaw('1')->from('tours')->whereColumn('tours.id', 'tour_schedules.ts_tour_id'))->lockForUpdate()->get();
            foreach ($schedules as $schedule) {
                $referenced = DB::table('book_tours')->where('b_tour_schedule_id', $schedule->id)->exists()
                    || (Schema::hasTable('schedule_guide_assignments') && DB::table('schedule_guide_assignments')->where('tour_schedule_id', $schedule->id)->exists());
                if ($referenced) {
                    $counts['blocked']++;
                    $this->error("Schedule #{$schedule->id} is still referenced; manual ownership repair is required.");

                    continue;
                }
                $counts['schedules']++;
                if ($this->option('apply')) {
                    $this->archive('tour_schedules', $schedule, 'Tour no longer exists; schedule has no booking or guide references.');
                    DB::table('tour_schedules')->where('id', $schedule->id)->delete();
                }
            }
            $logs = DB::table('admin_audit_logs')->whereNotNull('actor_id')->whereNotExists(fn ($q) => $q->selectRaw('1')->from('users')->whereColumn('users.id', 'admin_audit_logs.actor_id'))->lockForUpdate()->get();
            foreach ($logs as $log) {
                $counts['actors']++;
                if ($this->option('apply')) {
                    $this->archive('admin_audit_logs', $log, 'Actor no longer exists; original actor_id retained in this archive.');
                    DB::table('admin_audit_logs')->where('id', $log->id)->update(['actor_id' => null]);
                }
            }

            return $counts;
        });
        $this->info(($this->option('apply') ? 'Archived/repaired' : 'Dry run').": {$counts['schedules']} orphan schedule(s), {$counts['actors']} missing audit actor(s), {$counts['blocked']} blocked record(s).");

        return $counts['blocked'] ? self::FAILURE : self::SUCCESS;
    }

    private function archive(string $table, object $record, string $reason): void
    {
        // Never overwrite an earlier original snapshot. A duplicate aborts the transaction.
        DB::table('database_integrity_archives')->insert([
            'source_table' => $table,
            'source_id' => $record->id,
            'reason' => $reason,
            'original_record' => json_encode($record, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'archived_at' => now(),
        ]);
    }
}
