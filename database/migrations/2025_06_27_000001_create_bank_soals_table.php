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
        Schema::create('bank_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->enum('jenis_soal', ['pilihan_ganda', 'essay']);
            $table->text('pertanyaan');
            $table->string('pilihan_a')->nullable();
            $table->string('pilihan_b')->nullable();
            $table->string('pilihan_c')->nullable();
            $table->string('pilihan_d')->nullable();
            $table->text('jawaban_benar');
            $table->decimal('bobot', 5, 2)->default(1);
            $table->boolean('aktif')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soals');
    }
};
