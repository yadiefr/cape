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
        Schema::table('hero_banners', function (Blueprint $table) {
            // Hapus field background yang tidak diperlukan
            $table->dropColumn(['background_color', 'background_pattern']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_banners', function (Blueprint $table) {
            // Kembalikan field yang dihapus
            $table->string('background_color')->nullable()->after('background_opacity');
            $table->string('background_pattern')->nullable()->after('background_color');
        });
    }
};
