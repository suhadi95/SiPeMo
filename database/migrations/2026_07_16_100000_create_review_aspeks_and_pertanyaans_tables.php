<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_aspeks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('review_pertanyaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_aspek_id')->constrained('review_aspeks')->cascadeOnDelete();
            $table->text('teks_pertanyaan');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_pertanyaans');
        Schema::dropIfExists('review_aspeks');
    }
};
