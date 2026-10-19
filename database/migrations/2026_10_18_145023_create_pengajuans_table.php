<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('no_tiket')->unique();
            $table->enum('jenis_layanan', ['konsultasi', 'pengaduan', 'bantuan_hukum']);
            $table->enum('jenis_bantuan', ['litigasi', 'non_litigasi'])->nullable(); // hanya untuk bantuan hukum
            $table->string('kategori');
            $table->text('kronologi');
            $table->string('dokumen_bukti')->nullable();
            $table->enum('sumber', ['online', 'offline'])->default('online');
            $table->foreignId('diinput_oleh')->nullable()->constrained('users')->nullOnDelete(); // admin yang menginput formulir offline
            $table->enum('status', ['diajukan', 'diverifikasi', 'dianalisis', 'ditangani', 'selesai', 'dibatalkan'])->default('diajukan');
            $table->text('alasan_batal')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};