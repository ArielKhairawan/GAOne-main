<?php

namespace App\Http\Controllers\Sik;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sik\ScanQRRequest;
use App\Models\SuratIzinKeluar;
use App\Services\Sik\SecurityScanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecurityScanController extends Controller
{
    public function __construct(private SecurityScanService $scans)
    {
    }

    public function dashboard(Request $request): View
    {
        $this->authorize('scan', SuratIzinKeluar::class);

        return view('sik.security.dashboard', [
            'stats' => $this->scans->todayStats($request->user()),
        ]);
    }

    public function scanForm(): View
    {
        $this->authorize('scan', SuratIzinKeluar::class);

        return view('sik.security.scan');
    }

    /**
     * Endpoint AJAX yang dipanggil oleh JavaScript kamera HTML5 (dan oleh
     * halaman verifikasi QR) setiap kali sebuah kode berhasil dipindai.
     * Status TIDAK PERNAH diubah manual — hanya lewat proses scan ini.
     */
    public function scan(ScanQRRequest $request): JsonResponse
    {
        $result = $this->scans->scan($request->validated('token'), $request->user());

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'type' => $result['type'],
            'sik' => $result['sik'] ? [
                'nomor_sik' => $result['sik']->nomor_sik,
                'nama' => $result['sik']->user->name,
                'department' => $result['sik']->department,
                'status' => $result['sik']->status,
                'status_label' => $result['sik']->status_label,
                'jam_keluar_aktual' => optional($result['sik']->jam_keluar_aktual)->format('d M Y H:i'),
                'jam_kembali_aktual' => optional($result['sik']->jam_kembali_aktual)->format('d M Y H:i'),
            ] : null,
        ], $result['success'] ? 200 : 422);
    }

    /**
     * Halaman yang menjadi isi QR Code itu sendiri (URL). Berguna sebagai
     * fallback bila QR dipindai memakai aplikasi kamera biasa di luar
     * halaman Scan Security — tetap memerlukan login & permission sik.scan
     * sebelum status dapat diproses.
     */
    public function verify(string $token): View
    {
        $this->authorize('scan', SuratIzinKeluar::class);

        $sik = SuratIzinKeluar::with('user')->where('uuid', $token)->first();

        return view('sik.security.verify', ['sik' => $sik, 'token' => $token]);
    }

    public function historyToday(Request $request): View
    {
        $this->authorize('scan', SuratIzinKeluar::class);

        return view('sik.security.history', [
            'scans' => $this->scans->todayHistoryFor($request->user(), (int) config('sik.per_page')),
            'stats' => $this->scans->todayStats($request->user()),
        ]);
    }
}
