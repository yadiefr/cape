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
        Schema::table('jurusan', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('is_active');
            $table->string('gambar_header')->nullable()->after('logo');
            $table->text('visi')->nullable()->after('gambar_header');
            $table->text('misi')->nullable()->after('visi');
            $table->text('prospek_karir')->nullable()->after('misi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jurusan', function (Blueprint $table) {
            $table->dropColumn([
                'logo',
                'gambar_header',
                'visi',
                'misi',
                'prospek_karir'
            ]);
        });
    }
};
