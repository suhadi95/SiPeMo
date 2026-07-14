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
        Schema::table('moduls', function (Blueprint $table) {
            $table->foreignId('tahap_id')->nullable()->constrained('tahap_penyusunans')->onDelete('set null')->after('mata_kuliah_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('moduls', function (Blueprint $table) {
            $table->dropForeign(['tahap_id']);
            $table->dropColumn('tahap_id');
        });
    }
};
