<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('book_tours')) {
            return;
        }

        Schema::table('book_tours', function (Blueprint $table) {
            if (!Schema::hasColumn('book_tours', 'b_internal_note')) {
                $table->text('b_internal_note')->nullable()->after('b_cancel_reason');
            }

            if (!Schema::hasColumn('book_tours', 'b_assigned_staff_id')) {
                $table->unsignedBigInteger('b_assigned_staff_id')->nullable()->after('b_user_id');
                $table->index('b_assigned_staff_id', 'book_tours_assigned_staff_id_index');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('book_tours')) {
            return;
        }

        Schema::table('book_tours', function (Blueprint $table) {
            if ($this->hasIndex('book_tours', 'book_tours_assigned_staff_id_index')) {
                $table->dropIndex('book_tours_assigned_staff_id_index');
            }

            foreach (['b_internal_note', 'b_assigned_staff_id'] as $column) {
                if (Schema::hasColumn('book_tours', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    private function hasIndex(string $table, string $index): bool
    {
        return collect(Schema::getIndexes($table))->contains(function ($item) use ($index) {
            return ($item['name'] ?? null) === $index;
        });
    }
};
