<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Essential for database records
            $table->string('name'); // Nama Lengkap
            $table->string('username')->unique(); // Username untuk login
            $table->string('nisn')->unique(); // NISN, akan digunakan untuk password
            $table->string('whatsapp')->nullable(); // Nomor WhatsApp
            $table->string('password'); // Essential: Stores the actual hashed password
            $table->string('role')->default('pendaftar'); // Essential: Used by your registration logic
            $table->boolean('is_active')->default(true); // Essential: Used by your registration logic
            $table->rememberToken(); // Standard Laravel for "remember me"
            $table->timestamps(); // Standard Laravel for tracking creation/update times
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
