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
        Schema::table('guru', function (Blueprint $table) {
            $table->string('nip')->nullable(false)->change();
            $table->string('nama')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            $table->string('jenis_kelamin')->nullable(false)->change();
            $table->text('alamat')->nullable()->change();
            $table->string('no_hp')->nullable(false)->change();
            $table->string('foto')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->string('nip')->nullable()->change();
            $table->string('nama')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('jenis_kelamin')->nullable()->change();
            $table->text('alamat')->nullable(false)->change();
            $table->string('no_hp')->nullable()->change();
            $table->string('foto')->nullable()->change();
        });
    }
};
