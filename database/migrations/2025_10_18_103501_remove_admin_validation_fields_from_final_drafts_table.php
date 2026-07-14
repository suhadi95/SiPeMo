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
        Schema::table('final_drafts', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['validated_by']);
            
            // Hapus field-field yang terkait dengan validasi admin
            $table->dropColumn([
                'catatan_admin',
                'validated_at',
                'validated_by'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('final_drafts', function (Blueprint $table) {
            // Tambahkan kembali field-field yang dihapus
            $table->text('catatan_admin')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }
};
