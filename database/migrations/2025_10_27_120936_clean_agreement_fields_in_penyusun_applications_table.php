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
        Schema::table('penyusun_applications', function (Blueprint $table) {
            // Hapus kolom persetujuan lama yang tidak digunakan di form
            $table->dropColumn([
                'setuju_template_dan_waktu',
                'setuju_template',
                'setuju_waktu',
                'setuju_modul_2_sks',
                'setuju_modul_3_sks'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyusun_applications', function (Blueprint $table) {
            // Kembalikan kolom persetujuan lama jika rollback
            $table->boolean('setuju_template_dan_waktu')->default(false);
            $table->boolean('setuju_template')->default(false);
            $table->boolean('setuju_waktu')->default(false);
            $table->boolean('setuju_modul_2_sks')->default(false);
            $table->boolean('setuju_modul_3_sks')->default(false);
        });
    }
};
