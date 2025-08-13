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
        // Update materi table
        Schema::table('materi', function (Blueprint $table) {
            if (!Schema::hasColumn('materi', 'kelas_id')) {
                $table->unsignedBigInteger('kelas_id')->nullable()->after('guru_id');
            }
            if (!Schema::hasColumn('materi', 'mapel_id')) {
                $table->unsignedBigInteger('mapel_id')->nullable()->after('kelas_id');
            }
            if (!Schema::hasColumn('materi', 'file_name')) {
                $table->string('file_name')->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('materi', 'link_video')) {
                $table->text('link_video')->nullable()->after('file_name');
            }
            if (!Schema::hasColumn('materi', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('link_video');
            }
        });

        // Update tugas table
        Schema::table('tugas', function (Blueprint $table) {
            if (!Schema::hasColumn('tugas', 'guru_id')) {
                $table->unsignedBigInteger('guru_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('tugas', 'kelas_id')) {
                $table->unsignedBigInteger('kelas_id')->nullable()->after('guru_id');
            }
            if (!Schema::hasColumn('tugas', 'mapel_id')) {
                $table->unsignedBigInteger('mapel_id')->nullable()->after('kelas_id');
            }
            if (!Schema::hasColumn('tugas', 'tanggal_deadline')) {
                $table->date('tanggal_deadline')->nullable()->after('deadline');
            }
            if (!Schema::hasColumn('tugas', 'file_name')) {
                $table->string('file_name')->nullable()->after('file_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->dropColumn(['kelas_id', 'mapel_id', 'file_name', 'link_video', 'is_active']);
        });

        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn(['guru_id', 'kelas_id', 'mapel_id', 'tanggal_deadline', 'file_name']);
        });
    }
};
