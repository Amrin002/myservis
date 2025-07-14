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
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sparepart')->unique();
            $table->string('nama_sparepart');
            $table->string('merk');
            $table->string('kategori');
            $table->text('deskripsi')->nullable();
            $table->integer('stok')->default(0);
            $table->decimal('harga_beli', 12, 2);
            $table->decimal('harga_jual', 12, 2);
            $table->enum('status', ['tersedia', 'habis', 'segera_habis'])->default('tersedia');
            $table->string('lokasi_penyimpanan')->nullable();
            $table->date('tanggal_masuk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};
