<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->unique()->constrained('pengajuans')->cascadeOnDelete();
            $table->foreignId('advokat_id')->constrained('advokats')->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasans');
    }
};