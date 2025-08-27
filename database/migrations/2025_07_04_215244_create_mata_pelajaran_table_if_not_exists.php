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
        if (!Schema::hasTable('mata_pelajaran')) {
            Schema::create('mata_pelajaran', function (Blueprint $table) {
                $table->id();
                $table->string('kode', 10)->unique();
                $table->string('nama', 100);
                $table->text('kelas'); // Store as comma-separated values
                $table->foreignId('jurusan_id')->nullable()->constrained('jurusan')->onDelete('set null');
                $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');
                $table->string('jenis')->default('Wajib'); // Wajib, Kejuruan, Muatan Lokal
                $table->string('tahun_ajaran');
                $table->integer('kkm')->default(75);
                $table->text('deskripsi')->nullable();
                $table->text('materi_pokok')->nullable();
                $table->boolean('is_unggulan')->default(false);
                $table->timestamps();
                
                // Indexes for performance
                $table->index(['jenis', 'status']);
                $table->index(['tahun_ajaran']);
                $table->index(['guru_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mata_pelajaran');
    }
};
