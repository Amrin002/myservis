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
        Schema::create('servisans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_servis')->unique();
            $table->foreignId('pelanggan_id')->constrained()->onDelete('cascade');
            $table->string('tipe_barang');
            $table->string('merk_barang');
            $table->string('model_barang')->nullable();
            $table->text('kerusakan');
            $table->text('aksesoris')->nullable();
            $table->text('catatan_teknisi')->nullable();
            $table->enum('status', ['menunggu', 'proses', 'selesai', 'diambil', 'dibatalkan'])->default('menunggu');
            $table->decimal('estimasi_biaya', 12, 2);
            $table->decimal('biaya_akhir', 12, 2)->nullable();
            $table->decimal('dp', 12, 2)->default(0);
            $table->boolean('lunas')->default(false);
            $table->dateTime('tanggal_masuk');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servisans');
    }
};
