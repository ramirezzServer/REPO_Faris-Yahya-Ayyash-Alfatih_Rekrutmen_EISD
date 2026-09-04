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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'operator', 'warga'])->default('warga')->after('email');
            $table->string('no_hp')->nullable();
            $table->foreignId('kawasan_id')->nullable()->constrained('kawasan')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kawasan_id']);
            $table->dropColumn(['role', 'no_hp', 'kawasan_id']);
        });
    }
};
