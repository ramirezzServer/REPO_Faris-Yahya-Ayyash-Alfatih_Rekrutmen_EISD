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
        Schema::create('laporan_tumpukan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kawasan_id')->constrained('kawasan')->cascadeOnDelete();
            $table->string('lokasi');
            $table->text('deskripsi');
            $table->string('foto');
            $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
            $table->text('catatan_tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_tumpukan');
    }
};
