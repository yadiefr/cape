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
        // Hapus migrasi ini karena tabel users sudah tidak ada
        //Schema::table('users', function (Blueprint $table) {
        //    $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
        //});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::table('users', function (Blueprint $table) {
        //    $table->dropForeign(['kelas_id']);
        //    $table->dropColumn('kelas_id');
        //});
    }
};
