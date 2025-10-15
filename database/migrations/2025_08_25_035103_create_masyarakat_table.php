<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('masyarakat', function (Blueprint $table) {
            $table->string('nik', 16)->primary();
            $table->string('nama');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('telp', 25);
        });
    }
    /**
     * Reverse migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('masyarakat');
    }
};