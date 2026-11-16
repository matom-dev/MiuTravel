<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\BookTour;
use App\Models\Tour;
use App\Models\TourSchedule;
use App\Models\User;
use App\Services\DatabaseIntegrity;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    private function rejects(callable $write): void
    {
        try {
            $write();
            $this->fail('Database accepted an invalid relationship.');
        } catch (QueryException $e) {
            $this->assertNotEmpty($e->getMessage());
        }
    }

    private function booking(): BookTour
    {
        $tour = Tour::create(['t_title' => 'Integrity tour', 't_status' => 1]);
        $user = User::factory()->create();

        return BookTour::create([
            'b_tour_id' => $tour->id, 'b_user_id' => $user->id,
            'b_name' => 'Guest', 'b_email' => 'guest@example.test', 'b_phone' => '0901234567',
            'b_number_adults' => 1, 'b_number_child6' => 0, 'b_number_child2' => 0,
            'b_price_child6' => 0, 'b_price_child2' => 0, 'b_status' => BookTour::STATUS_PENDING,
        ]);
    }

    public function test_all_declared_relationships_have_foreign_keys_with_the_expected_delete_policy(): void
    {
        foreach (DatabaseIntegrity::relations() as [$table, $column, $parent, $delete]) {
            $fk = collect(Schema::getForeignKeys($table))->first(fn ($fk) => $fk['columns'] === [$column]);
            $this->assertNotNull($fk, "$table.$column");
            $this->assertSame($parent, $fk['foreign_table'], "$table.$column");
            $this->assertSame($delete, strtolower($fk['on_delete']), "$table.$column");
        }
    }

    public function test_raw_deletes_cannot_remove_booking_customer_tour_or_payment_history(): void
    {
        $booking = $this->booking();
        DB::table('vnpay_payments')->insert([
            'book_tour_id' => $booking->id, 'reference' => 'integrity-payment',
            'amount' => 1000, 'expires_at' => now()->addHour(),
        ]);
        $this->rejects(fn () => DB::table('book_tours')->where('id', $booking->id)->delete());
        $this->rejects(fn () => DB::table('tours')->where('id', $booking->b_tour_id)->delete());
        $this->rejects(fn () => DB::table('users')->where('id', $booking->b_user_id)->delete());
        $this->assertDatabaseHas('vnpay_payments', ['book_tour_id' => $booking->id]);
        $this->assertDatabaseHas('book_tours', ['id' => $booking->id]);
        $this->rejects(fn () => DB::table('vnpay_payments')->insert([
            'book_tour_id' => 999999, 'reference' => 'orphan-payment', 'amount' => 1000, 'expires_at' => now(),
        ]));
    }

    public function test_booking_schedule_must_belong_to_the_same_tour_and_day_is_unique(): void
    {
        $booking = $this->booking();
        $other = Tour::create(['t_title' => 'Other tour']);
        $schedule = TourSchedule::create([
            'ts_tour_id' => $other->id, 'ts_start_date' => '2027-01-02 08:00:00',
            'ts_end_date' => '2027-01-03 18:00:00', 'ts_number_guests' => 10,
        ]);
        $this->rejects(fn () => DB::table('book_tours')->where('id', $booking->id)->update(['b_tour_schedule_id' => $schedule->id]));
        $this->rejects(fn () => DB::table('tour_schedules')->insert([
            'ts_tour_id' => $other->id, 'ts_start_date' => '2027-01-02 12:00:00', 'ts_end_date' => '2027-01-04',
        ]));
        $valid = TourSchedule::create([
            'ts_tour_id' => $booking->b_tour_id, 'ts_start_date' => '2027-01-02', 'ts_end_date' => '2027-01-03', 'ts_number_guests' => 5,
        ]);
        $booking->update(['b_tour_schedule_id' => $valid->id]);
        $this->rejects(fn () => DB::table('tour_schedules')->where('id', $valid->id)->delete());
        $this->assertSame($valid->id, $booking->fresh()->schedule->id);
        $this->assertSame(4, $valid->remaining_seats);
        $booking->update(['b_status' => BookTour::STATUS_CANCELLED]);
        $this->assertSame(5, $valid->remaining_seats);
    }

    public function test_deleting_a_content_author_keeps_content_and_clears_attribution(): void
    {
        $author = User::factory()->create();
        $tour = Tour::create(['t_title' => 'Keep this tour', 't_user_id' => $author->id]);
        $author->delete();
        $this->assertDatabaseHas('tours', ['id' => $tour->id, 't_user_id' => null]);
    }

    public function test_categories_reject_cycles_and_referenced_parent_deletion(): void
    {
        $a = DB::table('categories')->insertGetId(['c_name' => 'A', 'c_slug' => 'a']);
        $b = DB::table('categories')->insertGetId(['c_name' => 'B', 'c_slug' => 'b', 'c_parent_id' => $a]);
        $c = DB::table('categories')->insertGetId(['c_name' => 'C', 'c_slug' => 'c', 'c_parent_id' => $b]);
        $this->rejects(fn () => DB::table('categories')->where('id', $a)->update(['c_parent_id' => $c]));
        $this->rejects(fn () => DB::table('categories')->where('id', $a)->update(['c_parent_id' => $a]));
        $this->rejects(fn () => DB::table('categories')->where('id', $a)->delete());
        $this->assertNull(DB::table('categories')->where('id', $a)->value('c_parent_id'));
    }

    public function test_comments_enforce_one_target_same_parent_target_and_immutable_thread(): void
    {
        $tour = Tour::create(['t_title' => 'Tour']);
        $other = Tour::create(['t_title' => 'Other']);
        $hotel = DB::table('hotels')->insertGetId(['h_name' => 'Hotel']);
        $this->rejects(fn () => DB::table('comments')->insert(['cm_content' => 'No target']));
        $this->rejects(fn () => DB::table('comments')->insert(['cm_tour_id' => $tour->id, 'cm_hotel_id' => $hotel]));
        $parent = DB::table('comments')->insertGetId(['cm_tour_id' => $tour->id]);
        $this->rejects(fn () => DB::table('comments')->insert(['cm_tour_id' => $other->id, 'cm_reply_id' => $parent]));
        $reply = DB::table('comments')->insertGetId(['cm_tour_id' => $tour->id, 'cm_reply_id' => $parent]);
        $this->rejects(fn () => DB::table('comments')->where('id', $parent)->update(['cm_tour_id' => $other->id]));
        $this->rejects(fn () => DB::table('comments')->where('id', $parent)->update(['cm_reply_id' => $reply]));
        DB::table('comments')->where('id', $parent)->update(['cm_status' => 2]);
        DB::table('comments')->where('id', $parent)->delete();
        $this->assertDatabaseMissing('comments', ['id' => $reply]);
    }

    public function test_acl_cascades_only_pivots_and_rejects_missing_users(): void
    {
        $user = User::factory()->create();
        $role = DB::table('roles')->insertGetId(['name' => 'integrity-role']);
        DB::table('role_user')->insert(['role_id' => $role, 'user_id' => $user->id]);
        $this->rejects(fn () => DB::table('role_user')->insert(['role_id' => $role, 'user_id' => 999999]));
        DB::table('roles')->where('id', $role)->delete();
        $this->assertDatabaseMissing('role_user', ['role_id' => $role]);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->artisan('db:audit-integrity')->assertExitCode(0);
    }

    public function test_agency_snapshot_is_immutable_and_does_not_follow_tour_transfer(): void
    {
        $owner = User::factory()->create();
        $agency = Agency::create(['user_id' => $owner->id, 'name' => 'Original agency']);
        $booking = $this->booking();
        $booking->tour->forceFill(['agency_id' => $agency->id])->save();
        // Earlier platform booking stays platform-owned, even after transferring the tour.
        $this->assertNull($booking->fresh()->agency_id);
        $second = $booking->replicate();
        $second->save();
        $this->assertSame($agency->id, $second->agency->id);
        $booking->tour->forceFill(['agency_id' => null])->save();
        $this->assertSame($agency->id, $second->fresh()->agency_id);
        $this->rejects(fn () => DB::table('book_tours')->where('id', $second->id)->update(['agency_id' => null]));
    }
}
