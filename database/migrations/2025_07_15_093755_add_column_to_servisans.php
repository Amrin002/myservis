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
        Schema::table('servisans', function (Blueprint $table) {
            // Tambahkan kolom tuser_id yang nullable
            $table->foreignId('tuser_id')
                ->nullable()
                ->constrained('tusers') // Sesuaikan dengan nama tabel untuk Tuser
                ->onDelete('set null');

            // Tambahkan kolom untuk tracking prioritas dan durasi servis
            $table->boolean('is_prioritas')->default(false);
            $table->integer('durasi_servis')->nullable(); // Durasi servis dalam menit
            $table->dateTime('mulai_servis')->nullable(); // Kapan mulai proses servis
            $table->dateTime('last_status_update')->nullable(); // Kapan terakhir status diupdate

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servisans', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan
            $table->dropForeignKey(['tuser_id']);
            $table->dropColumn([
                'tuser_id',
                'is_prioritas',
                'durasi_servis',
                'mulai_servis',
                'last_status_update'
            ]);
        });
    }
};
