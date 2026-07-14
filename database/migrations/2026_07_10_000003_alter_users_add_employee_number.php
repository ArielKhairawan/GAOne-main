<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Modul SIK membutuhkan "Nomor Karyawan" yang otomatis diambil dari
     * akun login. Kolom ini belum ada pada tabel users, sehingga
     * ditambahkan lewat migration alter (additive, tidak menghapus/mengubah
     * kolom lama apa pun) supaya modul lama tetap berjalan seperti biasa.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_number')->nullable()->unique()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('employee_number');
        });
    }
};
