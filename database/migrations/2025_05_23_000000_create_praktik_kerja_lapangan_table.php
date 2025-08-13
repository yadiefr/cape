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
        Schema::create('praktik_kerja_lapangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->string('nama_perusahaan');
            $table->text('alamat_perusahaan');
            $table->string('bidang_usaha');
            $table->string('nama_pembimbing');
            $table->string('telepon_pembimbing')->nullable();
            $table->string('email_pembimbing')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['pengajuan', 'berlangsung', 'selesai', 'gagal'])->default('pengajuan');
            $table->decimal('nilai_teknis', 5, 2)->nullable();
            $table->decimal('nilai_sikap', 5, 2)->nullable();
            $table->decimal('nilai_laporan', 5, 2)->nullable();
            $table->string('dokumen_laporan')->nullable();
            $table->string('surat_keterangan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('praktik_kerja_lapangan');
    }
};
