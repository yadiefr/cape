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
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            // Make schedule-related columns nullable to support assignments
            // Change enum to string and make nullable
            $table->string('hari')->nullable()->change();
            $table->time('jam_mulai')->nullable()->change();  
            $table->time('jam_selesai')->nullable()->change();
            // jam_ke is already nullable
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table) {
            // Revert back to non-nullable
            $table->string('hari')->nullable(false)->change();
            $table->time('jam_mulai')->nullable(false)->change();
            $table->time('jam_selesai')->nullable(false)->change();
        });
    }
};
