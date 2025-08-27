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
            // Only add the column if it doesn't exist
            if (!Schema::hasColumn('guru', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('jenis_kelamin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
