<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTourGuidesAndAssignmentsTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('tour_guides')) {
            Schema::create('tour_guides', function (Blueprint $table) {
                $table->id();
                $table->string('tg_name');
                $table->string('tg_role', 30)->default('guide');
                $table->string('tg_phone', 30)->nullable();
                $table->string('tg_email')->nullable();
                $table->string('tg_experience')->nullable();
                $table->string('tg_languages')->nullable();
                $table->string('tg_photo')->nullable();
                $table->unsignedTinyInteger('tg_status')->default(1);
                $table->unsignedBigInteger('tg_user_id')->nullable();
                $table->timestamps();

                $table->index(['tg_role', 'tg_status']);
            });
        }

        if (!Schema::hasTable('tour_guide_assignments')) {
            Schema::create('tour_guide_assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tga_tour_id');
                $table->unsignedBigInteger('tga_guide_id');
                $table->string('tga_role', 30);
                $table->timestamps();

                $table->unique(['tga_tour_id', 'tga_guide_id', 'tga_role'], 'tour_guide_assignments_unique');
                $table->index(['tga_tour_id', 'tga_role']);
                $table->index('tga_guide_id');
            });
        }

        $this->seedExistingGuideSnapshots();
    }

    public function down()
    {
        Schema::dropIfExists('tour_guide_assignments');
        Schema::dropIfExists('tour_guides');
    }

    private function seedExistingGuideSnapshots()
    {
        if (!Schema::hasTable('tours') || !Schema::hasTable('tour_guides') || !Schema::hasTable('tour_guide_assignments')) {
            return;
        }

        DB::table('tours')
            ->whereNotNull('t_guides')
            ->orderBy('id')
            ->get()
            ->each(function ($tour) {
                $guides = json_decode($tour->t_guides, true);
                if (!is_array($guides)) {
                    return;
                }

                foreach ($guides as $guide) {
                    $name = trim((string) ($guide['name'] ?? ''));
                    if ($name === '') {
                        continue;
                    }

                    $roleText = trim((string) ($guide['role'] ?? 'Hướng dẫn viên'));
                    $role = str_contains(mb_strtolower($roleText), 'trưởng') ? 'leader' : 'guide';

                    $existing = DB::table('tour_guides')
                        ->where('tg_name', $name)
                        ->when(!empty($guide['phone']), function ($query) use ($guide) {
                            $query->where('tg_phone', $guide['phone']);
                        })
                        ->first();

                    $guideId = $existing ? $existing->id : DB::table('tour_guides')->insertGetId([
                        'tg_name' => $name,
                        'tg_role' => $role,
                        'tg_phone' => $guide['phone'] ?? null,
                        'tg_email' => $guide['email'] ?? null,
                        'tg_experience' => $guide['experience'] ?? null,
                        'tg_languages' => $guide['languages'] ?? null,
                        'tg_photo' => $guide['photo'] ?? null,
                        'tg_status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('tour_guide_assignments')->updateOrInsert([
                        'tga_tour_id' => $tour->id,
                        'tga_guide_id' => $guideId,
                        'tga_role' => $role,
                    ], [
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]);
                }
            });
    }
}
