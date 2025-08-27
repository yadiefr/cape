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
        Schema::create('jadwal_pengawas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_ujian_id')->constrained('jadwal_ujian')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
            $table->enum('jenis_pengawas', ['utama', 'pendamping', 'cadangan'])->default('utama');
            $table->text('catatan')->nullable();
            $table->boolean('is_hadir')->default(false);
            $table->timestamp('waktu_hadir')->nullable();
            $table->text('keterangan_tidak_hadir')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index(['jadwal_ujian_id', 'guru_id']);
            $table->index('jenis_pengawas');
            
            // Unique constraint to prevent duplicate assignments
            $table->unique(['jadwal_ujian_id', 'guru_id', 'jenis_pengawas'], 'unique_pengawas_assignment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_pengawas');
    }
};
