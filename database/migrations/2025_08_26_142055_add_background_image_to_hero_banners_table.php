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
            $table->string('background_image')->nullable()->after('background_color');
            $table->enum('background_type', ['color', 'image', 'gradient'])->default('color')->after('background_color');
            $table->decimal('background_opacity', 3, 2)->default(1.00)->after('background_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_banners', function (Blueprint $table) {
            $table->dropColumn(['background_image', 'background_type', 'background_opacity']);
        });
    }
};
