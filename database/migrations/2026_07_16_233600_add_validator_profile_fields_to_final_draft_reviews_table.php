<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('final_draft_reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('final_draft_reviews', 'validator_institusi')) {
                $table->string('validator_institusi')->nullable()->after('rekomendasi_validator');
            }

            if (!Schema::hasColumn('final_draft_reviews', 'validator_bidang_keahlian')) {
                $table->string('validator_bidang_keahlian')->nullable()->after('validator_institusi');
            }

            if (!Schema::hasColumn('final_draft_reviews', 'validator_email_kontak')) {
                $table->string('validator_email_kontak')->nullable()->after('validator_bidang_keahlian');
            }

            if (!Schema::hasColumn('final_draft_reviews', 'validator_signature')) {
                $table->string('validator_signature')->nullable()->after('validator_email_kontak');
            }

            if (!Schema::hasColumn('final_draft_reviews', 'validator_report_completed_at')) {
                $table->timestamp('validator_report_completed_at')->nullable()->after('validator_signature');
            }
        });
    }

    public function down(): void
    {
        Schema::table('final_draft_reviews', function (Blueprint $table) {
            $columns = [
                'validator_institusi',
                'validator_bidang_keahlian',
                'validator_email_kontak',
                'validator_signature',
                'validator_report_completed_at',
            ];

            $existing = array_values(array_filter($columns, fn ($column) => Schema::hasColumn('final_draft_reviews', $column)));

            if (!empty($existing)) {
                $table->dropColumn($existing);
            }
        });
    }
};
