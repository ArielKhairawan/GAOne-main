<?php

namespace App\Services\Sik;

use App\Models\SuratIzinKeluar;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRGeneratorService
{
    /**
     * Isi QR Code TIDAK PERNAH berupa ID database, melainkan token acak
     * aman (UUID v4 milik SIK itu sendiri) yang dibungkus dalam URL
     * verifikasi. UUID sudah dibuat saat pengajuan (lihat SIKService),
     * sehingga method ini hanya membentuk payload/isi QR-nya.
     */
    public function payloadFor(SuratIzinKeluar $sik): string
    {
        return route('sik.security.verify', ['token' => $sik->uuid]);
    }

    /**
     * SVG dipilih sebagai format render (bukan PNG) karena tidak
     * membutuhkan ekstensi Imagick/GD tambahan di server, dan tetap tajam
     * saat dicetak atau ditampilkan di berbagai ukuran.
     */
    public function svg(SuratIzinKeluar $sik, int $size = 260): string
    {
        return QrCode::size($size)
            ->format('svg')
            ->errorCorrection('H')
            ->generate($this->payloadFor($sik));
    }

    /**
     * PNG base64 dipakai untuk disisipkan ke dalam PDF (DomPDF tidak bisa
     * merender elemen <img> yang menunjuk ke SVG data-URI dengan baik pada
     * semua environment, sehingga PNG lebih aman untuk cetak/PDF).
     */
    public function base64Png(SuratIzinKeluar $sik, int $size = 220): string
    {
        $png = QrCode::size($size)
            ->format('png')
            ->errorCorrection('H')
            ->generate($this->payloadFor($sik));

        return 'data:image/png;base64,'.base64_encode($png);
    }
}
