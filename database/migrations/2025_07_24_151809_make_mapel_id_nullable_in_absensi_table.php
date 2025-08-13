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
        Schema::table('absensi', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['mapel_id']);
            
            // Modify column to be nullable
            $table->unsignedBigInteger('mapel_id')->nullable()->change();
            
            // Re-add foreign key constraint with nullable
            $table->foreign('mapel_id')->references('id')->on('mata_pelajaran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['mapel_id']);
            
            // Revert column to not nullable
            $table->unsignedBigInteger('mapel_id')->nullable(false)->change();
            
            // Re-add foreign key constraint
            $table->foreign('mapel_id')->references('id')->on('mata_pelajaran')->onDelete('cascade');
        });
    }
};
