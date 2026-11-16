<?php

namespace Tests\Feature;

use App\Services\DatabaseIntegrity;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\CreatesApplication;

class DatabaseIntegrityUpgradeTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        $paths = array_values(array_filter(glob(database_path('migrations/*.php')), fn ($p) => ! str_contains($p, 'harden_database_relationships')));
        $this->artisan('migrate', ['--path' => $paths, '--realpath' => true, '--force' => true])->assertExitCode(0);
    }

    public function test_preflight_rejects_orphans_before_changing_schema_or_data(): void
    {
        $id = DB::table('tour_schedules')->insertGetId(['ts_tour_id' => 999999, 'ts_start_date' => '2027-01-01', 'ts_end_date' => '2027-01-02']);
        $migration = require database_path('migrations/2026_10_05_000001_harden_database_relationships.php');
        try {
            $migration->up();
            $this->fail('An orphan must fail preflight.');
        } catch (\RuntimeException $e) {
            $this->assertStringContainsString('orphan reference', $e->getMessage());
        }
        $this->assertFalse(Schema::hasColumn('book_tours', 'agency_id'));
        $this->assertDatabaseHas('tour_schedules', ['id' => $id, 'ts_tour_id' => 999999]);
    }

    public function test_safe_repairs_preserve_original_rows_and_migration_preserves_valid_history(): void
    {
        $orphan = DB::table('tour_schedules')->insertGetId(['ts_tour_id' => 999999, 'ts_start_date' => '2027-01-01', 'ts_end_date' => '2027-01-02', 'ts_number_guests' => 15]);
        $actor = DB::table('admin_audit_logs')->insertGetId(['actor_id' => 999999, 'action' => 'test.original', 'metadata' => '{"kept":true}']);
        $this->artisan('db:repair-safe-orphans')->assertExitCode(0);
        $this->assertDatabaseHas('tour_schedules', ['id' => $orphan]);
        $this->assertSame(0, DB::table('database_integrity_archives')->count());
        $this->artisan('db:repair-safe-orphans', ['--apply' => true])->assertExitCode(0);
        $this->assertDatabaseMissing('tour_schedules', ['id' => $orphan]);
        $this->assertDatabaseHas('admin_audit_logs', ['id' => $actor, 'actor_id' => null, 'metadata' => '{"kept":true}']);
        $saved = json_decode(DB::table('database_integrity_archives')->where('source_table', 'tour_schedules')->value('original_record'), true);
        $this->assertSame(999999, $saved['ts_tour_id']);
        $this->assertSame(15, $saved['ts_number_guests']);
        $audit = json_decode(DB::table('database_integrity_archives')->where('source_table', 'admin_audit_logs')->value('original_record'), true);
        $this->assertSame(999999, $audit['actor_id']);
        $root = DB::table('categories')->insertGetId(['c_name' => 'Root', 'c_slug' => 'root', 'c_parent_id' => 0]);
        $user = DB::table('users')->insertGetId(['name' => 'Owner', 'email' => 'owner@example.test', 'password' => 'unused']);
        $agency = DB::table('agencies')->insertGetId(['user_id' => $user, 'name' => 'Agency']);
        $tour = DB::table('tours')->insertGetId(['t_title' => 'Tour', 'agency_id' => $agency]);
        $booking = DB::table('book_tours')->insertGetId([
            'b_tour_id' => $tour, 'b_user_id' => $user, 'b_number_child6' => 0, 'b_number_child2' => 0,
            'b_price_child6' => 0, 'b_price_child2' => 0, 'b_status' => 3,
        ]);
        $this->artisan('migrate', ['--force' => true])->assertExitCode(0);
        $this->assertDatabaseHas('book_tours', ['id' => $booking, 'agency_id' => $agency, 'b_status' => 3]);
        $this->assertDatabaseHas('categories', ['id' => $root, 'c_parent_id' => null]);
        $this->assertSame([], (new DatabaseIntegrity)->problems());
        $this->artisan('db:repair-safe-orphans', ['--apply' => true])->assertExitCode(0);
        $this->assertSame(2, DB::table('database_integrity_archives')->count());
    }

    public function test_repairs_never_move_an_orphan_schedule_that_still_has_bookings(): void
    {
        $tour = DB::table('tours')->insertGetId(['t_title' => 'Existing tour']);
        $schedule = DB::table('tour_schedules')->insertGetId(['ts_tour_id' => 999999, 'ts_start_date' => '2027-01-01', 'ts_end_date' => '2027-01-02']);
        DB::table('book_tours')->insert([
            'b_tour_id' => $tour, 'b_tour_schedule_id' => $schedule,
            'b_number_child6' => 0, 'b_number_child2' => 0, 'b_price_child6' => 0, 'b_price_child2' => 0,
        ]);
        $this->artisan('db:repair-safe-orphans', ['--apply' => true])->assertExitCode(1);
        $this->assertDatabaseHas('tour_schedules', ['id' => $schedule]);
        $this->assertSame(0, DB::table('database_integrity_archives')->count());
    }
}
