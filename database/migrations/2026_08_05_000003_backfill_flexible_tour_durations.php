<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            !Schema::hasTable('tours')
            || !Schema::hasColumn('tours', 't_schedule')
            || !Schema::hasColumn('tours', 't_duration_days')
            || !Schema::hasColumn('tours', 't_duration_nights')
        ) {
            return;
        }

        DB::table('tours')
            ->select('id', 't_schedule', 't_duration_days', 't_duration_nights')
            ->orderBy('id')
            ->chunkById(100, function ($tours) {
                foreach ($tours as $tour) {
                    $days = $tour->t_duration_days;
                    $nights = $tour->t_duration_nights;

                    if ($days === null) {
                        $days = $this->parseDurationNumber($tour->t_schedule, ['ngày', 'ngay', 'days?', 'n']) ?: 1;
                    }

                    if ($nights === null) {
                        $nights = $this->parseDurationNumber($tour->t_schedule, ['đêm', 'dem', 'nights?', 'd']);
                        $nights = $nights === null ? max(0, (int) $days - 1) : $nights;
                    }

                    DB::table('tours')->where('id', $tour->id)->update([
                        't_duration_days' => max(1, (int) $days),
                        't_duration_nights' => max(0, (int) $nights),
                    ]);
                }
            });
    }

    public function down(): void
    {
        // Backfilled duration values are valid tour data, so rollback keeps them.
    }

    private function parseDurationNumber(?string $schedule, array $units): ?int
    {
        $schedule = trim((string) $schedule);

        if ($schedule === '') {
            return null;
        }

        $unitPattern = implode('|', $units);

        if (preg_match('/(\d+)\s*(?:' . $unitPattern . ')\b/iu', $schedule, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }
};
