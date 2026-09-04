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
        Schema::create('laporan_neraca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kawasan_id')->constrained('kawasan')->cascadeOnDelete();
            $table->foreignId('operator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('periode_kuota_id')->constrained('periode_kuota')->cascadeOnDelete();
            $table->date('tanggal_laporan');
            $table->decimal('timbulan_kg', 12, 2);
            $table->decimal('total_diolah_kg', 12, 2)->default(0);
            $table->decimal('residu_kg', 12, 2)->default(0);
            $table->enum('status', ['menunggu', 'terverifikasi', 'ditolak'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['kawasan_id', 'tanggal_laporan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_neraca');
    }
};
