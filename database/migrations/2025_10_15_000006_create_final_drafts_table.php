<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('final_drafts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyusun_application_id')->constrained('penyusun_applications')->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliahs')->cascadeOnDelete();
            $table->string('judul_modul');
            $table->text('deskripsi_modul')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('status', 50)->default('pending');
            $table->text('catatan_lpm')->nullable();
            $table->timestamp('uploaded_at');
            $table->timestamp('lpm_validated_at')->nullable();
            $table->foreignId('lpm_validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewer_validated_at')->nullable();
            $table->foreignId('reviewer_validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_reviewer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_drafts');
    }
};
