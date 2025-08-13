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
        Schema::table('siswa', function (Blueprint $table) {
            // Make tanggal_lahir nullable
            $table->date('tanggal_lahir')->nullable()->change();
            
            // Make other important fields nullable too for flexible import
            $table->string('nis', 20)->nullable()->change();
            $table->string('nisn', 20)->nullable()->change();
            $table->string('nama_lengkap')->nullable()->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->change();
            $table->string('tempat_lahir', 100)->nullable()->change();
            $table->string('agama', 50)->nullable()->change();
            $table->text('alamat')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->foreignId('kelas_id')->nullable()->change();
            $table->foreignId('jurusan_id')->nullable()->change();
            $table->year('tahun_masuk')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Reverse nullable changes (make them required again)
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->string('nis', 20)->nullable(false)->change();
            $table->string('nisn', 20)->nullable(false)->change();
            $table->string('nama_lengkap')->nullable(false)->change();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable(false)->change();
            $table->string('tempat_lahir', 100)->nullable(false)->change();
            $table->string('agama', 50)->nullable(false)->change();
            $table->text('alamat')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->foreignId('kelas_id')->nullable(false)->change();
            $table->foreignId('jurusan_id')->nullable(false)->change();
            $table->year('tahun_masuk')->nullable(false)->change();
        });
    }
};
