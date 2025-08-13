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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->unique();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('nisn', 20)->unique();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->text('alamat');
            $table->string('telepon', 20);
            $table->string('email')->nullable();
            $table->string('asal_sekolah');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('telepon_orangtua', 20)->nullable();
            $table->text('alamat_orangtua')->nullable();
            $table->foreignId('pilihan_jurusan_1')->constrained('jurusan');
            $table->foreignId('pilihan_jurusan_2')->nullable()->constrained('jurusan');
            $table->decimal('nilai_matematika', 5, 2)->nullable();
            $table->decimal('nilai_indonesia', 5, 2)->nullable();
            $table->decimal('nilai_inggris', 5, 2)->nullable();
            $table->enum('status', ['menunggu', 'diterima', 'ditolak', 'cadangan'])->default('menunggu');
            $table->string('dokumen_ijazah')->nullable();
            $table->string('dokumen_skhun')->nullable();
            $table->string('dokumen_foto')->nullable();
            $table->string('dokumen_kk')->nullable();
            $table->string('dokumen_ktp_ortu')->nullable();
            $table->datetime('tanggal_pendaftaran');
            $table->string('tahun_ajaran');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
