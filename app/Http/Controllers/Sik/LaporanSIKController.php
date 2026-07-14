<?php

namespace App\Http\Controllers\Sik;

use App\Exports\SuratIzinKeluarExport;
use App\Http\Controllers\Controller;
use App\Models\SuratIzinKeluar;
use App\Models\User;
use App\Services\Sik\SIKService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class LaporanSIKController extends Controller
{
    public function __construct(private SIKService $sik)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', SuratIzinKeluar::class);

        $filters = $request->only(['date_from', 'date_to', 'department', 'status', 'jenis_izin', 'employee_id']);

        return view('sik.laporan.index', [
            'items' => $this->sik->list($request->user(), $filters, (int) config('sik.per_page')),
            'stats' => $this->sik->dashboardStats($request->user()),
            'filters' => $filters,
            'statuses' => config('sik.statuses'),
            'jenisIzinOptions' => config('sik.jenis_izin'),
            'departments' => User::query()->whereNotNull('department')->distinct()->pluck('department'),
            'employees' => User::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'department', 'status', 'jenis_izin', 'employee_id']);
        $items = $this->sik->forExport($filters);

        $pdf = Pdf::loadView('sik.laporan.pdf', ['items' => $items, 'filters' => $filters]);

        return $pdf->stream('Laporan-SIK.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'department', 'status', 'jenis_izin', 'employee_id']);
        $items = $this->sik->forExport($filters);

        return Excel::download(new SuratIzinKeluarExport($items), 'Laporan-SIK.xlsx');
    }
}
