<?php

namespace App\Http\Controllers\Sik;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sik\ApproveSIKRequest;
use App\Models\SuratIzinKeluar;
use App\Services\Sik\ApprovalSIKService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApprovalSIKController extends Controller
{
    public function __construct(private ApprovalSIKService $approvals)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['department']);

        return view('sik.approvals.index', [
            'items' => $this->approvals->queueFor($request->user(), $filters, (int) config('sik.per_page')),
        ]);
    }

    public function show(SuratIzinKeluar $suratIzinKeluar): View
    {
        $this->authorize('view', $suratIzinKeluar);

        return view('sik.approvals.show', [
            'sik' => $suratIzinKeluar->load('user'),
        ]);
    }

    public function process(ApproveSIKRequest $request, SuratIzinKeluar $suratIzinKeluar): RedirectResponse
    {
        $manager = $request->user();
        $catatan = $request->validated('catatan');

        if ($request->validated('action') === 'approve') {
            $this->approvals->approve($suratIzinKeluar, $manager, $catatan);
            $message = 'Pengajuan SIK berhasil disetujui.';
        } else {
            $this->approvals->reject($suratIzinKeluar, $manager, $catatan);
            $message = 'Pengajuan SIK ditolak.';
        }

        return redirect()->route('sik.approvals.index')->with('status', $message);
    }
}
