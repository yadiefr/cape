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
        Schema::table('nilai', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('nilai', 'siswa_id')) {
                $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            }
            if (!Schema::hasColumn('nilai', 'bank_soal_id')) {
                $table->foreignId('bank_soal_id')->constrained('bank_soals')->onDelete('cascade');
            }
            if (!Schema::hasColumn('nilai', 'nilai')) {
                $table->decimal('nilai', 5, 2);
            }
            if (!Schema::hasColumn('nilai', 'catatan')) {
                $table->text('catatan')->nullable();
            }
            if (!Schema::hasColumn('nilai', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users');
            }
            if (!Schema::hasColumn('nilai', 'updated_by')) {
                $table->foreignId('updated_by')->nullable()->constrained('users');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropForeign(['bank_soal_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['siswa_id', 'bank_soal_id', 'nilai', 'catatan', 'created_by', 'updated_by']);
        });
    }
};
