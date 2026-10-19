<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->nullable()->constrained('pengajuans')->nullOnDelete();
            $table->string('judul');
            $table->enum('jenis', ['foto', 'berkas']);
            $table->string('file');
            $table->foreignId('diunggah_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasis');
    }
};