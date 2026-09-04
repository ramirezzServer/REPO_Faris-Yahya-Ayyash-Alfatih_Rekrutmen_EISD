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
        Schema::create('kawasan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kawasan')->unique();
            $table->string('nama_rw');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->integer('jumlah_kk')->default(0);
            $table->enum('status_siaga', ['aman', 'waspada', 'kritis'])->default('aman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kawasan');
    }
};
