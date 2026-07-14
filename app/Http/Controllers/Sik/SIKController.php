<?php

namespace App\Http\Controllers\Sik;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sik\StoreSIKRequest;
use App\Http\Requests\Sik\UpdateSIKRequest;
use App\Models\SuratIzinKeluar;
use App\Services\Sik\QRGeneratorService;
use App\Services\Sik\SIKService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SIKController extends Controller
{
    public function __construct(
        private SIKService $sik,
        private QRGeneratorService $qr,
    ) {
    }

    public function dashboard(Request $request): View
    {
        $user = $request->user();

        return view('sik.dashboard', [
            'stats' => $this->sik->dashboardStats($user),
            'monthlyChart' => $this->sik->monthlyChart($user),
            'recent' => $this->sik->list($user, [], 5),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', SuratIzinKeluar::class);

        return view('sik.create', [
            'sik' => new SuratIzinKeluar,
            'jenisIzinOptions' => config('sik.jenis_izin'),
            'kendaraanOptions' => config('sik.kendaraan_options'),
        ]);
    }

    public function store(StoreSIKRequest $request): RedirectResponse
    {
        $sik = $this->sik->create($request->validated(), $request->user());

        return redirect()->route('sik.show', $sik)
            ->with('status', 'Pengajuan Surat Izin Keluar berhasil dikirim dan menunggu persetujuan Manager.');
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', SuratIzinKeluar::class);

        $filters = $request->only(['date_from', 'date_to', 'department', 'status', 'jenis_izin']);

        return view('sik.index', [
            'items' => $this->sik->list($request->user(), $filters, (int) config('sik.per_page')),
            'filters' => $filters,
            'statuses' => config('sik.statuses'),
            'jenisIzinOptions' => config('sik.jenis_izin'),
        ]);
    }

    public function show(SuratIzinKeluar $suratIzinKeluar): View
    {
        $this->authorize('view', $suratIzinKeluar);

        $suratIzinKeluar->load(['user', 'manager', 'securityOut', 'securityIn', 'scans.security']);

        return view('sik.show', [
            'sik' => $suratIzinKeluar,
            'qrSvg' => in_array($suratIzinKeluar->status, ['approved', 'sedang_keluar', 'completed'], true)
                ? $this->qr->svg($suratIzinKeluar)
                : null,
        ]);
    }

    public function edit(SuratIzinKeluar $suratIzinKeluar): View
    {
        $this->authorize('update', $suratIzinKeluar);

        return view('sik.edit', [
            'sik' => $suratIzinKeluar,
            'jenisIzinOptions' => config('sik.jenis_izin'),
            'kendaraanOptions' => config('sik.kendaraan_options'),
        ]);
    }

    public function update(UpdateSIKRequest $request, SuratIzinKeluar $suratIzinKeluar): RedirectResponse
    {
        $this->sik->update($suratIzinKeluar, $request->validated());

        return redirect()->route('sik.show', $suratIzinKeluar)->with('status', 'Pengajuan SIK berhasil diperbarui.');
    }

    public function cancel(SuratIzinKeluar $suratIzinKeluar): RedirectResponse
    {
        $this->authorize('update', $suratIzinKeluar);

        $this->sik->cancel($suratIzinKeluar);

        return back()->with('status', 'Pengajuan SIK telah dibatalkan.');
    }

    public function pdf(SuratIzinKeluar $suratIzinKeluar)
    {
        $this->authorize('view', $suratIzinKeluar);

        $suratIzinKeluar->load(['user', 'manager', 'securityOut', 'securityIn']);

        $qrImage = in_array($suratIzinKeluar->status, ['approved', 'sedang_keluar', 'completed'], true)
            ? $this->qr->base64Png($suratIzinKeluar)
            : null;

        $pdf = Pdf::loadView('sik.pdf', ['sik' => $suratIzinKeluar, 'qrImage' => $qrImage]);

        return $pdf->stream("SIK-{$suratIzinKeluar->id}.pdf");
    }
}
