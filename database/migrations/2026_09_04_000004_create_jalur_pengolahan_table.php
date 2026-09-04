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
        Schema::create('jalur_pengolahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->enum('kategori', ['organik', 'anorganik']);
            $table->decimal('faktor_emisi_co2', 8, 4)->default(0);
            $table->string('ikon')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jalur_pengolahan');
    }
};
