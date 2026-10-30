<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviewer_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('reviewer_applications', 'nomor_rekening')) {
                $table->string('nomor_rekening', 50)->nullable()->after('nidn');
            }
            if (!Schema::hasColumn('reviewer_applications', 'nama_bank')) {
                $table->string('nama_bank', 100)->nullable()->after('nomor_rekening');
            }
            if (!Schema::hasColumn('reviewer_applications', 'nama_pemilik_rekening')) {
                $table->string('nama_pemilik_rekening', 255)->nullable()->after('nama_bank');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviewer_applications', function (Blueprint $table) {
            $columns = ['nomor_rekening', 'nama_bank', 'nama_pemilik_rekening'];
            $existing = array_values(array_filter($columns, fn ($col) => Schema::hasColumn('reviewer_applications', $col)));
            if (!empty($existing)) {
                $table->dropColumn($existing);
            }
        });
    }
};
