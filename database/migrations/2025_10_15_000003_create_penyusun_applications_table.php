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
        Schema::create('penyusun_applications', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penyusun');
            $table->string('email');
            $table->string('no_wa')->nullable();
            $table->string('nip')->nullable();
            $table->string('nidn')->nullable();
            $table->string('judul_bahan_ajar');
            $table->string('jurusan');
            $table->string('semester');
            $table->string('mata_kuliah');
            $table->foreignId('mata_kuliah_id')->nullable()->constrained('mata_kuliahs')->nullOnDelete();
            $table->string('draft_path')->nullable();
            $table->boolean('setuju_informasi')->default(false);
            $table->boolean('setuju_pelaksanaan')->default(false);
            $table->boolean('setuju_jml_modul')->default(false);
            $table->boolean('setuju_pembiayaan')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyusun_applications');
    }
};
