<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGaleriFotoTable extends Migration
{
    public function up(): void
    {
        Schema::create('galeri_foto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('galeri_id');
            $table->string('foto');
            $table->boolean('is_thumbnail')->default(false);
            $table->timestamps();

            $table->foreign('galeri_id')->references('id')->on('galeri')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_foto');
    }
}

return new CreateGaleriFotoTable();
