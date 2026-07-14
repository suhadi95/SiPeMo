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
            $table->boolean('setuju_pelaksanaan')->default(false);
            $table->boolean('setuju_jml_modul')->default(false);
            $table->boolean('setuju_pembiayaan')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyusun_applications', function (Blueprint $table) {
            $table->dropColumn(['setuju_pelaksanaan', 'setuju_jml_modul', 'setuju_pembiayaan']);
        });
    }
};
