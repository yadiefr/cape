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
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('metode_pembayaran')->nullable()->after('jumlah');
            $table->string('order_id')->nullable()->unique()->after('metode_pembayaran');
            $table->unsignedBigInteger('admin_id')->nullable()->after('order_id');
            $table->string('bukti_pembayaran')->nullable()->after('admin_id');
            
            $table->foreign('admin_id')->references('id')->on('admin')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn(['metode_pembayaran', 'order_id', 'admin_id', 'bukti_pembayaran']);
        });
    }
};
