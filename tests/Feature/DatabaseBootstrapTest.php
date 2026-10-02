<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\CreatesApplication;

class DatabaseBootstrapTest extends TestCase
{
    use CreatesApplication;

    public function test_all_migrations_bootstrap_an_empty_database_and_preserve_legacy_data(): void
    {
        // Do not use Tests\TestCase: its legacy schema helper masks missing migrations.
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');

        $this->artisan('migrate', ['--force' => true])->assertExitCode(0);

        foreach ([
            'users', 'password_resets', 'failed_jobs', 'jobs', 'personal_access_tokens',
            'locations', 'categories', 'tours', 'articles', 'hotels', 'comments',
            'book_tours', 'contacts', 'app_notifications', 'tour_schedules',
            'tour_guides', 'tour_guide_assignments', 'car_rentals', 'group_permission',
            'roles', 'permissions', 'role_user', 'permission_role',
            'booking_status_histories', 'admin_audit_logs', 'chat_messages',
        ] as $table) {
            $this->assertTrue(Schema::hasTable($table), "Missing table: {$table}");
        }

        $this->assertTrue(Schema::hasColumns('book_tours', ['b_code', 'b_tour_schedule_id', 'b_end_date', 'b_internal_note', 'b_assigned_staff_id']));
        $this->assertTrue(Schema::hasColumns('tours', ['t_activities', 't_guides', 't_duration_days', 't_duration_nights', 't_type']));
        $this->assertTrue(Schema::hasColumns('comments', ['cm_images', 'cm_rating']));

        $id = DB::table('contacts')->insertGetId(['name' => 'Preserve legacy contact']);
        $migration = require database_path('migrations/2026_07_22_000001_ensure_legacy_core_tables.php');
        $migration->up();
        $migration->down();
        $this->assertSame('Preserve legacy contact', DB::table('contacts')->where('id', $id)->value('name'));
        $this->artisan('migrate', ['--force' => true])->assertExitCode(0);
    }
}
