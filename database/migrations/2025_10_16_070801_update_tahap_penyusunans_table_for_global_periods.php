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
        Schema::table('tahap_penyusunans', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['penyusun_application_id']);
            $table->dropColumn('penyusun_application_id');
            
            // Add new columns for global periods
            $table->string('nama_periode')->after('tahap');
            $table->text('deskripsi_tahap')->nullable()->after('deskripsi');
            
            // Remove is_active column since we don't need manual activation anymore
            $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tahap_penyusunans', function (Blueprint $table) {
            // Add back the foreign key column
            $table->foreignId('penyusun_application_id')->constrained('penyusun_applications')->onDelete('cascade');
            
            // Drop the new columns
            $table->dropColumn(['nama_periode', 'deskripsi_tahap']);
            
            // Add back is_active column
            $table->boolean('is_active')->default(false);
        });
    }
};
