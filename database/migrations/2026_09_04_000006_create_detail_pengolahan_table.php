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
        Schema::create('detail_pengolahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_neraca_id')->constrained('laporan_neraca')->cascadeOnDelete();
            $table->foreignId('jalur_pengolahan_id')->constrained('jalur_pengolahan')->cascadeOnDelete();
            $table->decimal('tonase_kg', 12, 2);
            $table->decimal('faktor_emisi_saat_lapor', 8, 4)->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['laporan_neraca_id', 'jalur_pengolahan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengolahan');
    }
};
