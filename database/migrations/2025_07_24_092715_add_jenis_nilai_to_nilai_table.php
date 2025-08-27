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
    public function up()
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->string('jenis_nilai')->nullable()->after('tahun_ajaran');
            $table->index(['siswa_id', 'mapel_id', 'jenis_nilai', 'semester', 'tahun_ajaran']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropIndex(['siswa_id', 'mapel_id', 'jenis_nilai', 'semester', 'tahun_ajaran']);
            $table->dropColumn('jenis_nilai');
        });
    }
};
