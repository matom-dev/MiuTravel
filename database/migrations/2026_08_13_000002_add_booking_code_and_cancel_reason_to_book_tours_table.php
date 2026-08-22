<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('book_tours')) {
            return;
        }

        Schema::table('book_tours', function (Blueprint $table) {
            if (!Schema::hasColumn('book_tours', 'b_code')) {
                $table->string('b_code', 30)->nullable()->after('id');
            }

            if (!Schema::hasColumn('book_tours', 'b_cancel_reason')) {
                $table->text('b_cancel_reason')->nullable()->after('b_note');
            }
        });

        DB::table('book_tours')
            ->whereNull('b_code')
            ->orderBy('id')
            ->select(['id', 'created_at'])
            ->chunkById(100, function ($bookings) {
                foreach ($bookings as $booking) {
                    $year = $booking->created_at
                        ? date('Y', strtotime($booking->created_at))
                        : date('Y');

                    DB::table('book_tours')
                        ->where('id', $booking->id)
                        ->update(['b_code' => 'MT-' . $year . '-' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT)]);
                }
            });

        Schema::table('book_tours', function (Blueprint $table) {
            if (!$this->hasIndex('book_tours', 'book_tours_b_code_unique')) {
                $table->unique('b_code', 'book_tours_b_code_unique');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('book_tours')) {
            return;
        }

        Schema::table('book_tours', function (Blueprint $table) {
            if ($this->hasIndex('book_tours', 'book_tours_b_code_unique')) {
                $table->dropUnique('book_tours_b_code_unique');
            }

            foreach (['b_cancel_reason', 'b_code'] as $column) {
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
