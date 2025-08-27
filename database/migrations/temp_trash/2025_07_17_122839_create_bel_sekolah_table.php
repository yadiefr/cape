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
        Schema::create('bel_sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('hari')->nullable(); // hari dalam bahasa Indonesia (Senin, Selasa, dst)
            $table->time('waktu');
            $table->string('file_suara')->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->string('tipe')->default('normal'); // normal, masuk, istirahat, pulang, dsb
            $table->string('kode_warna')->nullable(); // kode warna untuk tampilan
            $table->string('ikon')->nullable()->default('bell'); // ikon font-awesome
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bel_sekolah');
    }
};
