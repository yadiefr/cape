<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToSiswaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Add indexes for frequent searches
            $table->index('nama_lengkap');
            $table->index('status');
            $table->index('tahun_masuk');
            $table->index(['kelas_id', 'jurusan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropIndex(['nama_lengkap']);
            $table->dropIndex(['status']);            $table->dropIndex(['tahun_masuk']);
            $table->dropIndex(['kelas_id', 'jurusan_id']);
        });
    }
}
