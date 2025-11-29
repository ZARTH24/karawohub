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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('kurir_id')->nullable(); // admin/tukang kirim
            $table->enum('status', [
                'siap_pickup',
                'pickup',
                'dalam_perjalanan',
                'hampir_sampai',
                'sampai',
                'gagal'
            ])->default('siap_pickup');
            $table->string('bukti')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
