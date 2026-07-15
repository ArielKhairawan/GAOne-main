<?php

namespace App\Http\Controllers;

use App\Models\AtkItem;
use App\Models\FacilityBooking;
use App\Models\PurchaseOrder;
use App\Models\ReportExport;
use App\Models\SurveyResponse;
use App\Models\TravelRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index', [
            // Kartu ringkasan di bagian atas halaman Laporan. Key di sini
            // ($type) dipakai langsung sebagai parameter route('reports.export', $type, ...)
            'reports' => [
                'travel' => [
                    'label' => 'Perjalanan Dinas',
                    'desc' => 'Rekap pengajuan dan estimasi biaya perjalanan dinas per departemen.',
                    'icon' => '✈️',
                    'color' => '#0ea5e9',
                ],
                'booking' => [
                    'label' => 'Booking Fasilitas',
                    'desc' => 'Ringkasan jumlah booking fasilitas/ruangan berdasarkan status.',
                    'icon' => '🏢',
                    'color' => '#f59e0b',
                ],
                'po' => [
                    'label' => 'Purchase Order',
                    'desc' => 'Rekap jumlah dan nilai purchase order berdasarkan status.',
                    'icon' => '🧾',
                    'color' => '#10b981',
                ],
                'csat' => [
                    'label' => 'Kepuasan Layanan (CSAT)',
                    'desc' => 'Skor rata-rata kepuasan dari seluruh survei yang masuk.',
                    'icon' => '⭐',
                    'color' => '#e11d48',
                ],
            ],
            'travel' => TravelRequest::selectRaw('department, count(*) as total, sum(estimated_cost) as cost')
                ->join('users', 'users.id', '=', 'travel_requests.user_id')->groupBy('department')->get(),
            'booking' => FacilityBooking::selectRaw('status, count(*) as total')->groupBy('status')->get(),
            'atk' => AtkItem::orderBy('stock')->limit(10)->get(),
            'po' => PurchaseOrder::selectRaw('status, count(*) as total, sum(total_amount) as amount')->groupBy('status')->get(),
            'csat' => round(SurveyResponse::avg('satisfaction_score') ?: 0, 2),
        ]);
    }

    public function export(Request $request, string $type)
    {
        $format = $request->validate(['format' => 'required|in:pdf,csv'])['format'];
        ReportExport::create(['user_id' => $request->user()->id, 'report_type' => $type, 'format' => $format, 'filters' => $request->query()]);

        if ($format === 'pdf') {
            return Pdf::loadView('reports.pdf', ['type' => $type, 'generatedAt' => now()])->download("ga1-$type-report.pdf");
        }

        return response("Report,Generated At\n$type,".now()->toDateTimeString()."\n", 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=ga1-$type-report.csv",
        ]);
    }
}
