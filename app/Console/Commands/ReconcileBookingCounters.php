<?php

namespace App\Console\Commands;

use App\Models\Tour;
use App\Models\TourSchedule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReconcileBookingCounters extends Command
{
    protected $signature = 'bookings:reconcile-counters {--fix : Clear legacy tour seat counters}';

    protected $description = 'Audit and optionally clear legacy tour seat counters that are no longer used by flexible-date bookings.';

    public function handle(): int
    {
        $fix = (bool) $this->option('fix');
        $differences = 0;

        $this->info($fix ? 'Clearing legacy booking counters...' : 'Auditing legacy booking counters...');

        Tour::query()->orderBy('id')->chunkById(100, function ($tours) use ($fix, &$differences) {
            foreach ($tours as $tour) {
                $expected = $this->expectedCounters();
                $current = [
                    'follow' => (int) $tour->t_follow,
                    'registered' => (int) $tour->t_number_registered,
                ];

                if ($expected !== $current) {
                    $differences++;
                    $this->line(sprintf(
                        'Tour #%d: legacy follow %d -> %d, legacy registered %d -> %d',
                        $tour->id,
                        $current['follow'],
                        $expected['follow'],
                        $current['registered'],
                        $expected['registered']
                    ));

                    if ($fix) {
                        $tour->forceFill([
                            't_follow' => 0,
                            't_number_registered' => 0,
                        ])->save();
                    }
                }
            }
        });

        if (class_exists(TourSchedule::class) && DB::getSchemaBuilder()->hasTable('tour_schedules')) {
            TourSchedule::query()->orderBy('id')->chunkById(100, function ($schedules) use ($fix, &$differences) {
                foreach ($schedules as $schedule) {
                    $expected = $this->expectedCounters();
                    $current = [
                        'follow' => (int) $schedule->ts_follow,
                        'registered' => (int) $schedule->ts_number_registered,
                    ];

                    if ($expected !== $current) {
                        $differences++;
                        $this->line(sprintf(
                            'Schedule #%d: legacy follow %d -> %d, legacy registered %d -> %d',
                            $schedule->id,
                            $current['follow'],
                            $expected['follow'],
                            $current['registered'],
                            $expected['registered']
                        ));

                        if ($fix) {
                            $schedule->forceFill([
                                'ts_follow' => 0,
                                'ts_number_registered' => 0,
                            ])->save();
                        }
                    }
                }
            });
        }

        $this->info($differences === 0 ? 'No legacy counter values found.' : $differences . ' legacy counter value set(s) found.');

        return self::SUCCESS;
    }

    private function expectedCounters(): array
    {
        return [
            'follow' => 0,
            'registered' => 0,
        ];
    }
}
