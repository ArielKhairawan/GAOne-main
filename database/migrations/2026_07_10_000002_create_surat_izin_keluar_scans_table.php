<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Log setiap kali QR SIK dipindai oleh Security, baik berhasil (scan
     * keluar / scan kembali) maupun gagal (QR tidak valid, sudah selesai,
     * belum disetujui, dst). Dipakai untuk "Riwayat Scan Hari Ini" dan
     * "Riwayat Scan" pada halaman detail SIK.
     */
    public function up(): void
    {
        Schema::create('surat_izin_keluar_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_izin_keluar_id')->nullable()->constrained('surat_izin_keluars')->cascadeOnDelete();
            $table->foreignId('security_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['keluar', 'kembali'])->nullable();
            $table->boolean('is_success')->default(true);
            $table->string('message')->nullable();
            $table->string('scanned_token')->nullable();
            $table->dateTime('scanned_at');
            $table->timestamps();

            $table->index(['scanned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_izin_keluar_scans');
    }
};
