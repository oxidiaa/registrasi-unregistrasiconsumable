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
        Schema::create('form_items', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->nullable();
            $table->string('nama_barang');
            $table->decimal('harga', 15, 2)->nullable();
            $table->string('estimasi_usia_pakai')->nullable();
            $table->string('kategori_penggunaan')->nullable();
            $table->string('kategori_ukuran')->nullable();
            $table->integer('min')->nullable();
            $table->integer('titik_order')->nullable();
            $table->integer('max')->nullable();
            $table->string('lead_time')->nullable();
            $table->boolean('is_b3')->default(false);
            $table->boolean('is_non_b3')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_items');
    }
};
