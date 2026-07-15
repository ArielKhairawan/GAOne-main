@extends('layouts.app')

@section('title', 'Monitoring Bahan Bakar')
@section('page-title', 'Monitoring Bahan Bakar')
@section('page-subtitle', 'Total ' . $logs->total() . ' data pengisian BBM.')

@section('content')

<!-- Aksi Ekspor & Tambah Data -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <div class="d-flex align-items-center gap-2">
        @can('fuel.export')
        <a class="btn btn-sm" href="{{ route('fuel.export.pdf', $filters) }}" style="background: rgba(239, 68, 68, 0.1); color: #EF4444; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/></svg>
            Unduh PDF
        </a>
        <a class="btn btn-sm" href="{{ route('fuel.export.excel', $filters) }}" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
            Unduh Excel
        </a>
        @endcan
        @can('fuel.create')
        <a class="btn btn-sm" href="{{ route('fuel.create') }}" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Pengisian
        </a>
        @endcan
    </div>
</div>

{{-- Stat Cards --}}
<div class="row g-4 mb-4" style="font-family: 'Poppins', sans-serif;">
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); border-left: 4px solid #F59E0B; box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px;">
            <div class="metric-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;"><span class="metric-label" style="font-size: 12px; font-weight: 600; color: #64748B;">Pengeluaran Bulan Ini</span><span class="metric-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #F59E0B"></span></div>
            <div class="metric-value" style="font-size: 18px; font-weight: 700; color: #0F172A;">Rp {{ number_format($stats['total_pengeluaran_bulan_ini'], 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); border-left: 4px solid #0EA5E9; box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px;">
            <div class="metric-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;"><span class="metric-label" style="font-size: 12px; font-weight: 600; color: #64748B;">Total Liter Bulan Ini</span><span class="metric-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #0EA5E9"></span></div>
            <div class="metric-value" style="font-size: 18px; font-weight: 700; color: #0F172A;">{{ number_format($stats['total_liter_bulan_ini'], 1) }} L</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); border-left: 4px solid #10B981; box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px;">
            <div class="metric-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;"><span class="metric-label" style="font-size: 12px; font-weight: 600; color: #64748B;">Total Pengisian</span><span class="metric-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #10B981"></span></div>
            <div class="metric-value" style="font-size: 18px; font-weight: 700; color: #0F172A;">{{ $stats['total_pengisian_bulan_ini'] }} Kali</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); border-left: 4px solid #EF4444; box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px;">
            <div class="metric-top" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;"><span class="metric-label" style="font-size: 12px; font-weight: 600; color: #64748B;">Rata-rata Konsumsi</span><span class="metric-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #EF4444"></span></div>
            <div class="metric-value" style="font-size: 18px; font-weight: 700; color: #0F172A;">{{ $stats['rata_rata_konsumsi'] }} km/l</div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-4 mb-4" style="font-family: 'Poppins', sans-serif;">
    <div class="col-lg-6">
        <div class="card" style="border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
            <div style="padding:16px 20px; border-bottom:1px solid var(--border); background: #F8FAFC;"><span class="metric-label" style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Pengeluaran BBM per Bulan</span></div>
            <div class="card-body p-3"><canvas id="chartFuelMonthly" height="220"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card" style="border-radius: 16px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
            <div style="padding:16px 20px; border-bottom:1px solid var(--border); background: #F8FAFC;"><span class="metric-label" style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Konsumsi BBM per Kendaraan (km/liter)</span></div>
            <div class="card-body p-3"><canvas id="chartConsumption" height="220"></canvas></div>
        </div>
    </div>
</div>

{{-- Filter Form --}}
<form method="get" class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px 24px; font-family: 'Poppins', sans-serif;">
    <div class="row g-3 align-items-center">
        <div class="col-md-2">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Tanggal Awal</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Tanggal Akhir</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Driver</label>
            <input type="text" name="driver" class="form-control form-control-sm" placeholder="Nama driver" value="{{ $filters['driver'] ?? '' }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Plat Kendaraan</label>
            <input type="text" name="plat_nomor" class="form-control form-control-sm" placeholder="B 1234 XXX" value="{{ $filters['plat_nomor'] ?? '' }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Jenis BBM</label>
            <select name="jenis_bahan_bakar" class="form-select form-select-sm" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
                <option value="">— Semua —</option>
                @foreach($fuelTypes as $type)
                    <option value="{{ $type }}" @selected(($filters['jenis_bahan_bakar'] ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mt-4 pt-2">
            <button class="btn btn-sm px-4" style="background: #475569; color: #ffffff; border: none; font-weight: 600; height: 36px; border-radius: 6px; font-size: 13px; width: 100%;">Terapkan Filter</button>
        </div>
    </div>
</form>

{{-- Data Table --}}
<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-family: 'Poppins', sans-serif; font-size: 13.5px; width: 100%;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Tanggal</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Plat Kendaraan</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Driver</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Jenis BBM</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Liter</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Total Harga</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Jarak</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Konsumsi</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; text-align: right; width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody style="border-top: none;">
                @forelse($logs as $log)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s ease;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 500;">
                        {{ $log->tanggal_pengisian->format('d M Y') }}
                    </td>
                    <td style="padding: 18px 24px; font-weight: 700; color: #0F172A;">
                        {{ $log->vehicle->plat_nomor ?? '—' }}
                    </td>
                    <td style="padding: 18px 24px; color: #475569; font-weight: 500;">
                        {{ $log->driver_name ?? '—' }}
                    </td>
                    <td style="padding: 18px 24px; color: #334155;">
                        <span style="background: rgba(71, 85, 105, 0.08); color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                            {{ $log->jenis_bahan_bakar }}
                        </span>
                    </td>
                    <td style="padding: 18px 24px; color: #0F172A; font-weight: 600;">
                        {{ number_format($log->jumlah_liter, 1) }} L
                    </td>
                    <td style="padding: 18px 24px; color: #0F172A; font-weight: 600;">
                        Rp {{ number_format($log->total_harga, 0, ',', '.') }}
                    </td>
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 500;">
                        {{ number_format($log->jarak_tempuh) }} km
                    </td>
                    <td style="padding: 18px 24px; color: #334155;">
                        @if($log->konsumsi_bbm)
                            <span style="background: rgba(16, 185, 129, 0.1); color: #10B981; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                {{ $log->konsumsi_bbm }} km/l
                            </span>
                        @else
                            <span style="color: #94A3B8;">—</span>
                        @endif
                    </td>
                    <td style="padding: 18px 24px; text-align: right;">
                        <div class="d-flex gap-2 justify-content-end">
                            @can('fuel.edit')
                            <a class="btn btn-sm" href="{{ route('fuel.edit', $log) }}" style="background: #ffffff; color: #F59E0B; border: 1px solid #FDE68A; font-weight: 600; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                Edit
                            </a>
                            @endcan
                            @can('fuel.delete')
                            <form method="post" action="{{ route('fuel.destroy', $log) }}" onsubmit="return confirm('Hapus data pengisian ini?')" style="display: inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="background: #ffffff; color: #EF4444; border: 1px solid #FEE2E2; font-weight: 600; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                    Hapus
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 64px 24px; color: #94A3B8;">
                        <div style="width: 56px; height: 56px; background: rgba(59, 130, 246, 0.1); color: #3B82F6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21\"></polyline></svg>
                        </div>
                        <div style="font-weight: 600; font-size: 14px; color: #64748B;">Data Kosong</div>
                        <div style="font-size: 12.5px; color: #94A3B8; margin-top: 4px;">Belum ada data pengisian BBM yang sesuai dengan kriteria pencarian.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4" style="font-family: 'Poppins', sans-serif;">
    {{ $logs->links() }}
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('chartFuelMonthly'), {
        type: 'bar',
        data: {
            labels: @json($monthlyChart->pluck('label')),
            datasets: [{
                label: 'Pengeluaran (Rp)',
                data: @json($monthlyChart->pluck('total')),
                backgroundColor: '#f59e0b',
                borderRadius: 6,
            }],
        },
        options: { responsive: true, plugins: { legend: { display: false } } },
    });

    new Chart(document.getElementById('chartConsumption'), {
        type: 'bar',
        data: {
            labels: @json($consumptionChart->pluck('plat_nomor')),
            datasets: [{
                label: 'Rata-rata km/liter',
                data: @json($consumptionChart->pluck('avg_konsumsi')),
                backgroundColor: '#3b82f6',
                borderRadius: 6,
            }],
        },
        options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } } },
    });
});
</script>
@endpush
