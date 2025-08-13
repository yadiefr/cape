<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('settings_jadwal', function (Blueprint $table) {
            $table->integer('jumlah_jam_pelajaran')->default(8)->after('jam_istirahat2_selesai');
            $table->integer('durasi_per_jam')->default(45)->after('jumlah_jam_pelajaran'); // Duration in minutes
        });
    }

    public function down()
    {
        Schema::table('settings_jadwal', function (Blueprint $table) {
            $table->dropColumn(['jumlah_jam_pelajaran', 'durasi_per_jam']);
        });
    }
};
