<?php

namespace App\Console\Commands;

use App\Models\BookTour;
use App\Models\Tour;
use App\Models\TourSchedule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReconcileBookingCounters extends Command
{
    protected $signature = 'bookings:reconcile-counters {--fix : Update tours and schedules to the computed values}';

    protected $description = 'Audit and optionally repair booking counters on tours and tour schedules.';

    public function handle(): int
    {
        $fix = (bool) $this->option('fix');
        $differences = 0;

        $this->info($fix ? 'Reconciling booking counters...' : 'Auditing booking counters...');

        Tour::query()->orderBy('id')->chunkById(100, function ($tours) use ($fix, &$differences) {
            foreach ($tours as $tour) {
                $expected = $this->expectedCounters(['b_tour_id' => $tour->id]);
                $current = [
                    'follow' => (int) $tour->t_follow,
                    'registered' => (int) $tour->t_number_registered,
                ];

                if ($expected !== $current) {
                    $differences++;
                    $this->line(sprintf(
                        'Tour #%d: follow %d -> %d, registered %d -> %d',
                        $tour->id,
                        $current['follow'],
                        $expected['follow'],
                        $current['registered'],
                        $expected['registered']
                    ));

                    if ($fix) {
                        $tour->forceFill([
                            't_follow' => $expected['follow'],
                            't_number_registered' => $expected['registered'],
                        ])->save();
                    }
                }
            }
        });

        if (class_exists(TourSchedule::class) && DB::getSchemaBuilder()->hasTable('tour_schedules')) {
            TourSchedule::query()->orderBy('id')->chunkById(100, function ($schedules) use ($fix, &$differences) {
                foreach ($schedules as $schedule) {
                    $expected = $this->expectedCounters(['b_tour_schedule_id' => $schedule->id]);
                    $current = [
                        'follow' => (int) $schedule->ts_follow,
                        'registered' => (int) $schedule->ts_number_registered,
                    ];

                    if ($expected !== $current) {
                        $differences++;
                        $this->line(sprintf(
                            'Schedule #%d: follow %d -> %d, registered %d -> %d',
                            $schedule->id,
                            $current['follow'],
                            $expected['follow'],
                            $current['registered'],
                            $expected['registered']
                        ));

                        if ($fix) {
                            $schedule->forceFill([
                                'ts_follow' => $expected['follow'],
                                'ts_number_registered' => $expected['registered'],
                            ])->save();
                        }
                    }
                }
            });
        }

        $this->info($differences === 0 ? 'No counter drift found.' : $differences . ' counter difference(s) found.');

        return self::SUCCESS;
    }

    private function expectedCounters(array $where): array
    {
        $base = BookTour::query()->where($where);

        $guestExpression = 'COALESCE(b_number_adults,0) + COALESCE(b_number_children,0) + COALESCE(b_number_child6,0) + COALESCE(b_number_child2,0)';

        return [
            'follow' => (int) (clone $base)->where('b_status', 1)->selectRaw('SUM(' . $guestExpression . ') as total')->value('total'),
            'registered' => (int) (clone $base)->whereIn('b_status', [2, 3, 4])->selectRaw('SUM(' . $guestExpression . ') as total')->value('total'),
        ];
    }
}
