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
            $table->boolean('setuju_template')->default(false)->after('setuju_template_dan_waktu');
            $table->boolean('setuju_waktu')->default(false)->after('setuju_template');
            $table->boolean('setuju_informasi')->default(false)->after('setuju_waktu');
            $table->boolean('setuju_modul_2_sks')->default(false)->after('setuju_informasi');
            $table->boolean('setuju_modul_3_sks')->default(false)->after('setuju_modul_2_sks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyusun_applications', function (Blueprint $table) {
            $table->dropColumn([
                'setuju_template',
                'setuju_waktu',
                'setuju_informasi',
                'setuju_modul_2_sks',
                'setuju_modul_3_sks'
            ]);
        });
    }
};
