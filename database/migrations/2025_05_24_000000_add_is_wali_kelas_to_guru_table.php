<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->boolean('is_wali_kelas')->default(false)->after('foto');
        });
    }

    public function down()
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn('is_wali_kelas');
        });
    }
};
