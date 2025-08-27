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
            $table->string('no_telp_ayah', 25)->nullable()->after('pekerjaan_ayah');
            $table->string('no_telp_ibu', 25)->nullable()->after('pekerjaan_ibu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['no_telp_ayah', 'no_telp_ibu']);
        });
    }
};
