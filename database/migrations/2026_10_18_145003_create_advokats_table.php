<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advokats', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('foto')->nullable();
            $table->string('spesialisasi');
            $table->text('bio')->nullable();
            $table->string('kontak')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advokats');
    }
};