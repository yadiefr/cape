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
        Schema::create('school_bells', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama bel (Masuk, Istirahat, Pulang, dll)
            $table->string('day_of_week')->nullable(); // Hari dalam seminggu (Monday, Tuesday, etc.)
            $table->time('time'); // Waktu bel berbunyi (format: HH:MM:SS)
            $table->string('sound_file')->nullable(); // Path ke file audio
            $table->text('description')->nullable(); // Deskripsi tambahan
            $table->boolean('is_active')->default(true); // Status aktif/non-aktif
            $table->string('type')->default('regular'); // Tipe bel (regular, ujian, khusus)
            $table->string('color_code')->default('#3B82F6'); // Kode warna untuk UI
            $table->string('icon')->default('bell'); // Icon untuk UI (font-awesome)
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
        Schema::dropIfExists('school_bells');
    }
};
