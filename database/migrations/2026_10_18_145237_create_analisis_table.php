<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->unique()->constrained('pengajuans')->cascadeOnDelete();
            $table->text('ringkasan_kasus');
            $table->text('rekomendasi');
            $table->enum('bentuk_bantuan', ['litigasi', 'non_litigasi']);
            $table->foreignId('dibuat_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analisis');
    }
};