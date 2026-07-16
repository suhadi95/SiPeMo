<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('final_draft_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('final_draft_id')->constrained('final_drafts')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->string('hasil_penilaian', 50);
            $table->text('catatan_revisi')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamps();
        });

        Schema::create('final_draft_review_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('final_draft_review_id')->constrained('final_draft_reviews')->cascadeOnDelete();
            $table->foreignId('review_pertanyaan_id')->nullable()->constrained('review_pertanyaans')->nullOnDelete();
            $table->string('aspek_nama');
            $table->text('teks_pertanyaan');
            $table->unsignedTinyInteger('skor');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::table('final_drafts', function (Blueprint $table) {
            $table->string('hasil_penilaian', 50)->nullable()->after('catatan_reviewer');
        });
    }

    public function down(): void
    {
        Schema::table('final_drafts', function (Blueprint $table) {
            $table->dropColumn('hasil_penilaian');
        });

        Schema::dropIfExists('final_draft_review_answers');
        Schema::dropIfExists('final_draft_reviews');
    }
};
