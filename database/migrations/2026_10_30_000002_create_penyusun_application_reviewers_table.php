<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyusun_application_reviewers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyusun_application_id')->constrained('penyusun_applications')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();

            $table->unique(['penyusun_application_id', 'reviewer_id'], 'app_reviewer_unique');
        });

        // Migrasi data penugasan yang sudah ada di mata_kuliahs.reviewer_id ke tabel pivot baru
        try {
            $existingCourses = DB::table('mata_kuliahs')
                ->whereNotNull('reviewer_id')
                ->select('id', 'reviewer_id')
                ->get();

            foreach ($existingCourses as $mk) {
                $applications = DB::table('penyusun_applications')
                    ->where('mata_kuliah_id', $mk->id)
                    ->pluck('id');

                foreach ($applications as $appId) {
                    DB::table('penyusun_application_reviewers')->updateOrInsert(
                        [
                            'penyusun_application_id' => $appId,
                            'reviewer_id' => $mk->reviewer_id,
                        ],
                        [
                            'assigned_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        } catch (\Throwable $e) {
            // Jangan gagalkan migration jika migrasi data awal opsional
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('penyusun_application_reviewers');
    }
};
