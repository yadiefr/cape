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
        Schema::table('siswa', function (Blueprint $table) {
            $table->boolean('is_ketua_kelas')->default(false)->after('status');
            $table->boolean('is_bendahara')->default(false)->after('is_ketua_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('is_ketua_kelas');
            $table->dropColumn('is_bendahara');
        });
    }
};
