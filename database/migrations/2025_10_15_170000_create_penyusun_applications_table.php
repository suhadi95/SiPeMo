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
            $table->string('nuptk')->nullable();
            $table->string('judul_bahan_ajar');
            $table->string('jurusan');
            $table->string('semester');
            $table->string('mata_kuliah');
            $table->string('draft_path'); 
            $table->boolean('setuju_template_dan_waktu')->default(false);
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
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


