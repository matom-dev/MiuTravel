<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Shared by the read-only audit command and the migration preflight. */
class DatabaseIntegrity
{
    /** [child table, column, parent table, on delete]. */
    public static function relations(): array
    {
        return [
            ['locations', 'l_user_id', 'users', 'set null'],
            ['categories', 'c_user_id', 'users', 'set null'],
            ['categories', 'c_parent_id', 'categories', 'restrict'],
            ['tours', 't_location_id', 'locations', 'restrict'],
            ['tours', 't_user_id', 'users', 'set null'],
            ['tours', 'agency_id', 'agencies', 'restrict'],
            ['articles', 'a_category_id', 'categories', 'restrict'],
            ['articles', 'a_user_id', 'users', 'set null'],
            ['hotels', 'h_location_id', 'locations', 'restrict'],
            ['hotels', 'h_user_id', 'users', 'set null'],
            ['comments', 'cm_user_id', 'users', 'set null'],
            ['comments', 'cm_article_id', 'articles', 'cascade'],
            ['comments', 'cm_hotel_id', 'hotels', 'cascade'],
            ['comments', 'cm_tour_id', 'tours', 'cascade'],
            ['comments', 'cm_reply_id', 'comments', 'cascade'],
            ['contacts', 'c_user_id', 'users', 'set null'],
            ['book_tours', 'b_tour_id', 'tours', 'restrict'],
            ['book_tours', 'b_user_id', 'users', 'restrict'],
            ['book_tours', 'b_assigned_staff_id', 'users', 'set null'],
            ['book_tours', 'b_tour_schedule_id', 'tour_schedules', 'restrict'],
            ['book_tours', 'agency_id', 'agencies', 'restrict'],
            ['tour_schedules', 'ts_tour_id', 'tours', 'cascade'],
            ['tour_guides', 'tg_user_id', 'users', 'set null'],
            ['tour_guide_assignments', 'tga_tour_id', 'tours', 'cascade'],
            ['tour_guide_assignments', 'tga_guide_id', 'tour_guides', 'restrict'],
            ['car_rentals', 'cr_location_id', 'locations', 'restrict'],
            ['car_rentals', 'cr_user_id', 'users', 'set null'],
            ['permissions', 'group_permission_id', 'group_permission', 'restrict'],
            ['role_user', 'role_id', 'roles', 'cascade'],
            ['role_user', 'user_id', 'users', 'cascade'],
            ['permission_role', 'permission_id', 'permissions', 'cascade'],
            ['permission_role', 'role_id', 'roles', 'cascade'],
            ['booking_status_histories', 'book_tour_id', 'book_tours', 'restrict'],
            ['booking_status_histories', 'changed_by', 'users', 'set null'],
            ['vnpay_payments', 'book_tour_id', 'book_tours', 'restrict'],
            ['admin_audit_logs', 'actor_id', 'users', 'set null'],
            ['app_notifications', 'receiver_id', 'users', 'cascade'],
            ['chat_messages', 'user_id', 'users', 'set null'],
            ['agencies', 'user_id', 'users', 'restrict'],
            ['booking_passengers', 'book_tour_id', 'book_tours', 'restrict'],
            ['agency_transactions', 'book_tour_id', 'book_tours', 'restrict'],
            ['agency_transactions', 'recorded_by', 'users', 'restrict'],
            ['schedule_guide_assignments', 'tour_schedule_id', 'tour_schedules', 'cascade'],
            ['schedule_guide_assignments', 'tour_guide_id', 'tour_guides', 'restrict'],
        ];
    }

    public function problems(): array
    {
        $problems = [];
        foreach (self::relations() as [$table, $column, $parent]) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
                continue; // New columns/tables are added after migration preflight.
            }
            $query = DB::table("$table as child")->leftJoin("$parent as parent", "child.$column", '=', 'parent.id')
                ->whereNotNull("child.$column")->whereNull('parent.id');
            if (in_array($column, ['c_parent_id', 'cm_reply_id'], true)) {
                $query->where("child.$column", '!=', 0); // Legacy root sentinel, normalized by migration.
            }
            $count = $query->count();
            if ($count) {
                $problems[] = "$table.$column -> $parent.id: $count orphan reference(s)";
            }
        }
        $missingTours = DB::table('book_tours')->whereNull('b_tour_id')->count();
        if ($missingTours) {
            $problems[] = "book_tours: $missingTours booking(s) have no tour";
        }
        $mismatch = DB::table('book_tours as b')->join('tour_schedules as s', 's.id', '=', 'b.b_tour_schedule_id')
            ->where(fn ($q) => $q->whereNull('b.b_tour_id')->orWhereColumn('b.b_tour_id', '!=', 's.ts_tour_id'))->count();
        if ($mismatch) {
            $problems[] = "book_tours: $mismatch booking(s) reference a schedule of another tour";
        }
        $duplicates = DB::table('tour_schedules')->selectRaw('ts_tour_id, DATE(ts_start_date) as departure_day, COUNT(*) as total')
            ->groupBy('ts_tour_id')->groupByRaw('DATE(ts_start_date)')->havingRaw('COUNT(*) > 1')->get()->count();
        if ($duplicates) {
            $problems[] = "tour_schedules: $duplicates duplicate tour/departure-day group(s)";
        }
        $invalid = DB::table('comments')->whereRaw(self::invalidCommentTarget())->count();
        if ($invalid) {
            $problems[] = "comments: $invalid row(s) must reference exactly one article, hotel or tour";
        }
        $badReplies = DB::table('comments as c')->join('comments as p', 'p.id', '=', 'c.cm_reply_id')
            ->whereRaw('COALESCE(c.cm_article_id, 0) <> COALESCE(p.cm_article_id, 0) OR COALESCE(c.cm_hotel_id, 0) <> COALESCE(p.cm_hotel_id, 0) OR COALESCE(c.cm_tour_id, 0) <> COALESCE(p.cm_tour_id, 0)')->count();
        if ($badReplies) {
            $problems[] = "comments: $badReplies reply/parent target mismatch(es)";
        }
        foreach (['categories' => 'c_parent_id', 'comments' => 'cm_reply_id'] as $table => $column) {
            $parents = DB::table($table)->pluck($column, 'id')->all();
            $done = [];
            foreach ($parents as $id => $parent) {
                $path = [];
                $cursor = $id;
                while ($cursor && isset($parents[$cursor]) && ! isset($done[$cursor])) {
                    if (isset($path[$cursor])) {
                        $problems[] = "$table: parent cycle involving id=$cursor";
                        break;
                    }
                    $path[$cursor] = true;
                    $cursor = $parents[$cursor];
                }
                $done += $path;
            }
        }

        return $problems;
    }

    public static function invalidCommentTarget(string $prefix = ''): string
    {
        return "(({$prefix}cm_article_id IS NOT NULL) + ({$prefix}cm_hotel_id IS NOT NULL) + ({$prefix}cm_tour_id IS NOT NULL)) <> 1";
    }
}
