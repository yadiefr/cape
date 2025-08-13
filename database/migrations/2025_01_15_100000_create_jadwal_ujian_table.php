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
        Schema::create('jadwal_ujian', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian');
            $table->string('jenis_ujian'); // uts, uas, quiz, tugas, praktek, etc.
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('guru_id')->nullable()->constrained('guru')->onDelete('set null');
            $table->date('tanggal');
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai');
            $table->integer('durasi'); // in minutes
            $table->foreignId('bank_soal_id')->nullable()->constrained('bank_soal')->onDelete('set null');
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangan')->onDelete('set null');
            $table->enum('status', ['draft', 'scheduled', 'active', 'completed', 'cancelled'])->default('draft');
            $table->text('deskripsi')->nullable();
            $table->boolean('acak_soal')->default(false);
            $table->boolean('acak_jawaban')->default(false);
            $table->boolean('tampilkan_hasil')->default(true);
            $table->integer('max_peserta')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['tanggal', 'waktu_mulai']);
            $table->index(['kelas_id', 'tanggal']);
            $table->index(['mata_pelajaran_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_ujian');
    }
};
