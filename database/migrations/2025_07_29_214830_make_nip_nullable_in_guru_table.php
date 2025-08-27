<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guru', function (Blueprint $table) {
            // First drop the existing unique constraint
            $table->dropUnique(['nip']);
        });
        
        // Make NIP nullable
        Schema::table('guru', function (Blueprint $table) {
            $table->string('nip')->nullable()->change();
        });
        
        // Add unique constraint that allows null values (MySQL approach)
        Schema::table('guru', function (Blueprint $table) {
            $table->unique('nip', 'guru_nip_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guru', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('guru_nip_unique');
            
            // Make NIP not nullable and add back the standard unique constraint
            $table->string('nip')->nullable(false)->unique()->change();
        });
    }
};
