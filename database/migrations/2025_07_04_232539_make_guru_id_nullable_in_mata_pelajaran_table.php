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
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['guru_id']);
            
            // Make guru_id nullable
            $table->foreignId('guru_id')->nullable()->change();
            
            // Re-add foreign key constraint with nullable option
            $table->foreign('guru_id')->references('id')->on('guru')->onDelete('set null');
            
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('mata_pelajaran', 'status')) {
                $table->string('status')->default('Aktif')->after('kkm');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            // Drop the nullable foreign key
            $table->dropForeign(['guru_id']);
            
            // Make guru_id required again
            $table->foreignId('guru_id')->change();
            
            // Re-add foreign key constraint
            $table->foreign('guru_id')->references('id')->on('guru')->onDelete('cascade');
        });
    }
};
