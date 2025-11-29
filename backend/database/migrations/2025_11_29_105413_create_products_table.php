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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('nama');
            $table->string('motif')->nullable();
            $table->string('bahan')->nullable();
            $table->integer('stok');
            $table->integer('harga');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['pending', 'aktif', 'blokir'])->default('pending'); // validasi admin
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
