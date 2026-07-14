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
        Schema::create('publication_moduls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyusun_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('final_draft_id')->constrained()->onDelete('cascade');
            $table->string('judul_modul');
            $table->text('deskripsi_modul')->nullable();
            
            // File Final Modul
            $table->string('final_modul_file_path');
            $table->string('final_modul_file_name');
            
            // File Sertifikat HKI
            $table->string('sertifikat_hki_file_path');
            $table->string('sertifikat_hki_file_name');
            
            // Informasi Rekening
            $table->string('nama_bank');
            $table->string('nomor_rekening');
            $table->string('nama_pemilik_rekening');
            
            // Status dan Validasi
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('uploaded_at');
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication_moduls');
    }
};
