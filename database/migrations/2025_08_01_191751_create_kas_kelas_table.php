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
        Schema::create('kas_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->string('kategori'); // Contoh: iuran_bulanan, acara_kelas, pembelian_alat, dll
            $table->string('keterangan');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->string('diinput_oleh'); // nama siswa yang input
            $table->foreignId('siswa_id')->nullable()->constrained('siswa')->onDelete('set null'); // siswa yang input
            $table->text('catatan')->nullable();
            $table->string('bukti_transaksi')->nullable(); // path file bukti
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['kelas_id', 'tanggal']);
            $table->index(['kelas_id', 'tipe']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_kelas');
    }
};
