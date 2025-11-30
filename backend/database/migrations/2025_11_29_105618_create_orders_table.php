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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('vendor_id'); // 1 order 1 vendor (MVP)
            $table->integer('total_produk');
            $table->integer('ongkir');
            $table->integer('biaya_admin')->default(0);
            $table->integer('total');
            $table->text('alamat');
            $table->string('catatan')->nullable();

            $table->enum('status', [
                'pending',
                'bayar',
                'gagal_bayar',
                'diproses',
                'siap_pickup',
                'dikirim',
                'sampai',
                'selesai',
                'dibatalkan'
            ])->default('pending');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
