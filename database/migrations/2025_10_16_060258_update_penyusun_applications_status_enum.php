<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing data first
        DB::statement("UPDATE penyusun_applications SET status = 'approved' WHERE status = 'disetujui'");
        DB::statement("UPDATE penyusun_applications SET status = 'rejected' WHERE status = 'ditolak'");
        
        // Modify the enum column
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE penyusun_applications MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the enum column
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE penyusun_applications MODIFY COLUMN status ENUM('pending', 'disetujui', 'ditolak') DEFAULT 'pending'");
        }
        
        // Revert data
        DB::statement("UPDATE penyusun_applications SET status = 'disetujui' WHERE status = 'approved'");
        DB::statement("UPDATE penyusun_applications SET status = 'ditolak' WHERE status = 'rejected'");
    }
};
