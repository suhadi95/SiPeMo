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
        Schema::create('reviewer_applications', function (Blueprint $table) {
            $table->id();
            $table->string('nama_reviewer');
            $table->string('email')->unique();
            $table->string('no_wa');
            $table->string('nip')->nullable();
            $table->string('nidn');
            $table->string('sertifikasi_path');
            $table->string('sertifikasi_name');
            $table->string('status', 30)->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviewer_applications');
    }
};
