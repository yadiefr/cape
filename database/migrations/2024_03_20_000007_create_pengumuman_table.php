<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->unsignedBigInteger('author_id');
            $table->string('author_type'); // 'App\Models\Admin' or 'App\Models\Guru'
            $table->string('target_role'); // all, admin, guru, siswa
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('lampiran')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_homepage')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengumuman');
    }
};