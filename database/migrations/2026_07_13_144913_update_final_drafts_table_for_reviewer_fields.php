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
            $table->string('status', 50)->default('pending')->change();
            $table->timestamp('reviewer_validated_at')->nullable()->after('catatan_lpm');
            $table->foreignId('reviewer_validated_by')->nullable()->after('reviewer_validated_at')->constrained('users')->onDelete('set null');
            $table->text('catatan_reviewer')->nullable()->after('reviewer_validated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('final_drafts', function (Blueprint $table) {
            $table->dropForeign(['reviewer_validated_by']);
            $table->dropColumn([
                'reviewer_validated_at',
                'reviewer_validated_by',
                'catatan_reviewer'
            ]);
            // We don't revert status column from string to enum because reverting changes in some DBs can be problematic
        });
    }
};
