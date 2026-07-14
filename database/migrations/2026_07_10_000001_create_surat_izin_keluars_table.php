<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Modul baru: Surat Izin Keluar (SIK).
     *
     * Catatan desain: project ini tidak memiliki tabel/model `departments`
     * terpisah (departemen user disimpan sebagai string pada kolom
     * `users.department`). Agar tidak membuat struktur baru yang tidak perlu
     * dan tetap konsisten dengan modul-modul lain (Travel, ATK, Meeting, dst
     * yang juga memakai string department), kolom `department` di sini
     * menyimpan salinan (snapshot) `users.department` pada saat pengajuan
     * dibuat. Snapshot dipakai (bukan selalu join ke users) supaya riwayat
     * SIK tetap akurat walau department user berubah di kemudian hari.
     */
    public function up(): void
    {
        Schema::create('surat_izin_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sik')->nullable()->unique();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('department')->nullable()->index();

            $table->enum('jenis_izin', ['dinas', 'pribadi'])->index();
            $table->text('keperluan');
            $table->string('kendaraan')->nullable();
            $table->text('catatan')->nullable();
            $table->string('lampiran')->nullable();

            $table->dateTime('jam_keluar_rencana');
            $table->dateTime('jam_kembali_rencana');
            $table->dateTime('jam_keluar_aktual')->nullable();
            $table->dateTime('jam_kembali_aktual')->nullable();

            $table->enum('status', [
                'pending_approval',
                'approved',
                'rejected',
                'sedang_keluar',
                'completed',
                'cancelled',
            ])->default('pending_approval')->index();

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->text('approval_note')->nullable();

            $table->foreignId('security_out_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('security_out_at')->nullable();

            $table->foreignId('security_in_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('security_in_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_izin_keluars');
    }
};
