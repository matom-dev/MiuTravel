<?php

use App\Services\DatabaseIntegrity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // SQLite must be able to disable FK checks while rebuilding existing tables.
    public $withinTransaction = false;

    public function up(): void
    {
        $driver = DB::getDriverName();
        if (! in_array($driver, ['sqlite', 'mysql', 'mariadb'], true)) {
            throw new RuntimeException('Relationship migration supports MySQL/MariaDB and SQLite only.');
        }
        $problems = (new DatabaseIntegrity)->problems();
        if ($problems) {
            throw new RuntimeException("Integrity preflight failed; no records were deleted. Run php artisan db:audit-integrity.\n".implode("\n", $problems));
        }

        // Only normalize the documented legacy root sentinel; never guess orphan ownership.
        DB::table('categories')->where('c_parent_id', 0)->update(['c_parent_id' => null]);
        DB::table('comments')->where('cm_reply_id', 0)->update(['cm_reply_id' => null]);
        Schema::table('categories', fn (Blueprint $t) => $t->unsignedBigInteger('c_parent_id')->nullable()->default(null)->change());
        Schema::table('comments', fn (Blueprint $t) => $t->unsignedBigInteger('cm_reply_id')->nullable()->change());

        if (! Schema::hasColumn('book_tours', 'agency_id')) {
            Schema::table('book_tours', fn (Blueprint $t) => $t->unsignedBigInteger('agency_id')->nullable()->index());
            // Historical ownership is only recoverable from the currently associated tour.
            DB::table('book_tours')->update(['agency_id' => DB::raw('(SELECT agency_id FROM tours WHERE tours.id = book_tours.b_tour_id)')]);
        }
        if (! Schema::hasTable('schedule_guide_assignments')) {
            Schema::create('schedule_guide_assignments', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('tour_schedule_id');
                $t->unsignedBigInteger('tour_guide_id');
                $t->string('role', 30);
                $t->timestamps();
                $t->unique(['tour_schedule_id', 'tour_guide_id', 'role'], 'schedule_guide_role_unique');
            });
        }

        // Replace legacy cascades as well as adding missing constraints. Introspecting
        // columns makes this work with imported databases whose FK names differ.
        $groups = collect(DatabaseIntegrity::relations())->groupBy(fn ($r) => $r[0]);
        foreach ($groups as $table => $relations) {
            $existing = Schema::getForeignKeys($table);
            $changes = $relations->filter(function ($r) use ($existing) {
                foreach ($existing as $fk) {
                    if ($fk['columns'] === [$r[1]] && $fk['foreign_table'] === $r[2]
                        && $fk['foreign_columns'] === ['id'] && strtolower($fk['on_delete']) === $r[3]) {
                        return false;
                    }
                }

                return true;
            });
            if ($changes->isEmpty()) {
                continue;
            }
            Schema::table($table, function (Blueprint $t) use ($changes, $existing, $table, $driver) {
                foreach ($changes as [, $column, $parent, $onDelete]) {
                    foreach ($existing as $fk) {
                        if ($fk['columns'] === [$column]) {
                            $t->dropForeign($driver === 'sqlite' ? $fk['columns'] : $fk['name']);
                        }
                    }
                    $t->foreign($column, $table.'_'.$column.'_foreign')->references('id')->on($parent)->onDelete($onDelete)->onUpdate('restrict');
                }
            });
        }

        foreach (DatabaseIntegrity::relations() as [$table, $column]) {
            $indexed = collect(Schema::getIndexes($table))->contains(fn ($index) => ($index['columns'][0] ?? null) === $column);
            if (! $indexed) {
                Schema::table($table, fn (Blueprint $t) => $t->index($column));
            }
        }

        if (! $this->hasIndex('tour_schedules', 'schedules_id_tour_unique')) {
            Schema::table('tour_schedules', fn (Blueprint $t) => $t->unique(['id', 'ts_tour_id'], 'schedules_id_tour_unique'));
        }
        $hasPair = collect(Schema::getForeignKeys('book_tours'))->contains(fn ($fk) => $fk['columns'] === ['b_tour_schedule_id', 'b_tour_id']);
        if (! $hasPair) {
            Schema::table('book_tours', fn (Blueprint $t) => $t->foreign(['b_tour_schedule_id', 'b_tour_id'], 'booking_schedule_tour_foreign')
                ->references(['id', 'ts_tour_id'])->on('tour_schedules')->restrictOnDelete()->restrictOnUpdate());
        }
        // A generated date keeps uniqueness even when callers supply different times.
        // Added after all rebuilds: SQLite cannot copy values into a generated column.
        if (! Schema::hasColumn('tour_schedules', 'departure_day')) {
            Schema::table('tour_schedules', fn (Blueprint $t) => $t->date('departure_day')->virtualAs('date(ts_start_date)'));
        }
        if (! $this->hasIndex('tour_schedules', 'schedules_tour_departure_unique')) {
            Schema::table('tour_schedules', fn (Blueprint $t) => $t->unique(['ts_tour_id', 'departure_day'], 'schedules_tour_departure_unique'));
        }

        $this->guard('comments', 'insert', DatabaseIntegrity::invalidCommentTarget('NEW.').' OR NEW.cm_reply_id = NEW.id OR (NEW.cm_reply_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM comments p WHERE p.id = NEW.cm_reply_id AND COALESCE(p.cm_article_id, 0) = COALESCE(NEW.cm_article_id, 0) AND COALESCE(p.cm_hotel_id, 0) = COALESCE(NEW.cm_hotel_id, 0) AND COALESCE(p.cm_tour_id, 0) = COALESCE(NEW.cm_tour_id, 0)))', 'Invalid comment target or parent');
        // Discussion identity is immutable; this also prevents reply cycles and changing
        // a parent target underneath existing replies. Content and moderation remain editable.
        $this->guard('comments', 'update', DatabaseIntegrity::invalidCommentTarget('NEW.').' OR COALESCE(NEW.cm_reply_id, 0) <> COALESCE(OLD.cm_reply_id, 0) OR COALESCE(NEW.cm_article_id, 0) <> COALESCE(OLD.cm_article_id, 0) OR COALESCE(NEW.cm_hotel_id, 0) <> COALESCE(OLD.cm_hotel_id, 0) OR COALESCE(NEW.cm_tour_id, 0) <> COALESCE(OLD.cm_tour_id, 0)', 'Comment target and parent cannot change');
        $this->guard('book_tours', 'insert', 'COALESCE(NEW.agency_id, 0) <> COALESCE((SELECT agency_id FROM tours WHERE id = NEW.b_tour_id), 0)', 'Booking agency must match the tour at creation');
        $this->guard('book_tours', 'delete', '1 = 1', 'Booking history must be retained; cancel the booking instead');
        $this->guard('book_tours', 'update', 'COALESCE(NEW.agency_id, 0) <> COALESCE(OLD.agency_id, 0) OR COALESCE(NEW.b_tour_id, 0) <> COALESCE(OLD.b_tour_id, 0)', 'Booking tour and agency snapshots cannot change');
        foreach (['insert', 'update'] as $event) {
            $this->guard('book_tours', $event, 'NEW.b_tour_id IS NULL', 'A booking must reference its tour', 'schedule');
            $this->guardCategoryCycle($event);
        }
    }

    private function guardCategoryCycle(string $event): void
    {
        if (DB::getDriverName() === 'sqlite') {
            $this->guard('categories', $event, 'NEW.c_parent_id = NEW.id OR EXISTS (WITH RECURSIVE ancestors AS (SELECT id, c_parent_id FROM categories WHERE id = NEW.c_parent_id UNION ALL SELECT c.id, c.c_parent_id FROM categories c JOIN ancestors a ON c.id = a.c_parent_id) SELECT 1 FROM ancestors WHERE id = NEW.id)', 'Category parent cycle is not allowed');

            return;
        }
        // MariaDB 10.4 cannot resolve a recursive CTE inside a trigger reliably.
        // An iterative ancestor walk also works on MySQL without recursive CTE support.
        $name = "integrity_categories_{$event}_identity";
        DB::unprepared("DROP TRIGGER IF EXISTS $name");
        DB::unprepared("CREATE TRIGGER $name BEFORE $event ON categories FOR EACH ROW
            BEGIN
                DECLARE ancestor_id BIGINT UNSIGNED;
                DECLARE hops INT DEFAULT 0;
                SET ancestor_id = NEW.c_parent_id;
                WHILE ancestor_id IS NOT NULL DO
                    IF ancestor_id = NEW.id OR hops >= 10000 THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Category parent cycle is not allowed';
                    END IF;
                    SET ancestor_id = (SELECT c.c_parent_id FROM categories c WHERE c.id = ancestor_id);
                    SET hops = hops + 1;
                END WHILE;
            END");
    }

    private function guard(string $table, string $event, string $condition, string $message, string $suffix = 'identity'): void
    {
        $name = "integrity_{$table}_{$event}_{$suffix}";
        DB::unprepared("DROP TRIGGER IF EXISTS $name");
        if (DB::getDriverName() === 'sqlite') {
            DB::unprepared("CREATE TRIGGER $name BEFORE $event ON $table WHEN $condition BEGIN SELECT RAISE(ABORT, '$message'); END");
        } else {
            DB::unprepared("CREATE TRIGGER $name BEFORE $event ON $table FOR EACH ROW BEGIN IF $condition THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '$message'; END IF; END");
        }
    }

    private function hasIndex(string $table, string $name): bool
    {
        return collect(Schema::getIndexes($table))->contains(fn ($index) => $index['name'] === $name);
    }

    public function down(): void
    {
        // Restoring destructive cascades or dropping ownership snapshots is unsafe.
        // Explicitly refuse instead of claiming a rollback succeeded without undoing it.
        throw new RuntimeException('Relationship hardening is forward-only. Restore a verified backup or write a reviewed corrective migration.');
    }
};
