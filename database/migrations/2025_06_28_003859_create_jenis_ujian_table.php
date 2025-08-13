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
        Schema::create('jenis_ujian', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255)->unique();
            $table->string('kode', 10)->unique();
            $table->text('deskripsi')->nullable();
            $table->integer('durasi_default')->nullable()->comment('Durasi default dalam menit');
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(1);
            $table->timestamps();

            $table->index(['is_active', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jenis_ujian');
    }
};
