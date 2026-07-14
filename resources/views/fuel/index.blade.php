@extends('layouts.app')

@section('title', 'Monitoring Bahan Bakar')
@section('page-title', 'Monitoring Bahan Bakar')
@section('page-subtitle', 'Total ' . $logs->total() . ' data pengisian BBM tercatat.')

@section('content')

<!-- =========================================================
     HEADER (HANYA TOMBOL)
     ========================================================= -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <div class="d-flex align-items-center gap-2">
        @can('fuel.export')
        <a class="btn btn-sm" href="{{ route('fuel.export.pdf', $filters) }}" style="background: rgba(225,29,72,.1); color: #E11D48; border: none; font-weight: 600; padding: 8px 16px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Unduh PDF</a>
        <a class="btn btn-sm" href="{{ route('fuel.export.excel', $filters) }}" style="background: rgba(16,185,129,.1); color: #10B981; border: none; font-weight: 600; padding: 8px 16px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Unduh Excel</a>
        @endcan
        @can('fuel.create')
        <a class="btn btn-sm" href="{{ route('fuel.create') }}" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 8px 16px; border-radius: 8px; display: flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Data
        </a>
        @endcan
    </div>
</div>

<!-- =========================================================
     1. KARTU STATISTIK (FLEXBOX ANTI-HANCUR)
     ========================================================= -->
<div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
    <!-- Pengeluaran -->
    <div style="flex: 1 1 220px; min-width: 0;">
        <div class="metric-card" style="padding: 20px; background: #ffffff; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="font-size: 13px; font-weight: 600; color: #64748B; margin-bottom: 12px;">Pengeluaran Bulan Ini</div>
            <div style="font-size: 22px; font-weight: 700; color: #D97706; line-height: 1.2;">Rp {{ number_format($stats['total_pengeluaran_bulan_ini'], 0, ',', '.') }}</div>
        </div>
    </div>
    <!-- Total Liter -->
    <div style="flex: 1 1 220px; min-width: 0;">
        <div class="metric-card" style="padding: 20px; background: #ffffff; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="font-size: 13px; font-weight: 600; color: #64748B; margin-bottom: 12px;">Total Liter Bulan Ini</div>
            <div style="font-size: 22px; font-weight: 700; color: #0EA5E9; line-height: 1.2;">{{ number_format($stats['total_liter_bulan_ini'], 1) }} <span style="font-size: 14px; font-weight: 600;">L</span></div>
        </div>
    </div>
    <!-- Total Pengisian -->
    <div style="flex: 1 1 220px; min-width: 0;">
        <div class="metric-card" style="padding: 20px; background: #ffffff; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="font-size: 13px; font-weight: 600; color: #64748B; margin-bottom: 12px;">Frekuensi Pengisian</div>
            <div style="font-size: 22px; font-weight: 700; color: #10B981; line-height: 1.2;">{{ $stats['total_pengisian_bulan_ini'] }} <span style="font-size: 14px; font-weight: 600;">Kali</span></div>
        </div>
    </div>
    <!-- Konsumsi Rata-rata -->
    <div style="flex: 1 1 220px; min-width: 0;">
        <div class="metric-card" style="padding: 20px; background: #ffffff; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="font-size: 13px; font-weight: 600; color: #64748B; margin-bottom: 12px;">Rata-rata Konsumsi</div>
            <div style="font-size: 22px; font-weight: 700; color: #E11D48; line-height: 1.2;">{{ $stats['rata_rata_konsumsi'] }} <span style="font-size: 14px; font-weight: 600;">km/l</span></div>
        </div>
    </div>
</div>

<!-- =========================================================
     2. GRAFIK (FLEXBOX PASTI 50:50 & PENUH)
     ========================================================= -->
<div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
    <!-- Chart Pengeluaran -->
    <div style="flex: 1 1 400px; min-width: 0;">
        <div class="metric-card" style="padding: 24px; border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <div style="font-weight: 700; font-size: 15px; color: #0F172A; margin-bottom: 20px;">Tren Pengeluaran BBM per Bulan</div>
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="chartFuelMonthly"></canvas>
            </div>
        </div>
    </div>
    <!-- Chart Konsumsi -->
    <div style="flex: 1 1 400px; min-width: 0;">
        <div class="metric-card" style="padding: 24px; border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
            <div style="font-weight: 700; font-size: 15px; color: #0F172A; margin-bottom: 20px;">Rata-rata Konsumsi per Kendaraan</div>
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="chartConsumption"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================
     3. FILTER PENCARIAN (FLEXBOX RAPI)
     ========================================================= -->
<form method="get" class="metric-card mb-4" style="padding: 20px 24px; border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
        <div style="flex: 1 1 140px;">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; margin-bottom: 6px; display: block;">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 13px; width: 100%;" value="{{ $filters['date_from'] ?? '' }}">
        </div>
        <div style="flex: 1 1 140px;">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; margin-bottom: 6px; display: block;">Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 13px; width: 100%;" value="{{ $filters['date_to'] ?? '' }}">
        </div>
        <div style="flex: 2 1 200px;">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; margin-bottom: 6px; display: block;">Pencarian (Driver / Plat)</label>
            <input type="text" name="plat_nomor" class="form-control" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 13px; width: 100%;" placeholder="Ketik kata kunci..." value="{{ $filters['plat_nomor'] ?? $filters['driver'] ?? '' }}">
        </div>
        <div style="flex: 1 1 140px;">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; margin-bottom: 6px; display: block;">Jenis BBM</label>
            <select name="jenis_bahan_bakar" class="form-select" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 13px; width: 100%;">
                <option value="">— Semua Jenis —</option>
                @foreach($fuelTypes as $type)
                    <option value="{{ $type }}" @selected(($filters['jenis_bahan_bakar'] ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex: 1 1 120px;">
            <button class="btn w-100" style="background: var(--surface-3); color: var(--text); border: 1px solid var(--border); font-weight: 600; height: 38px; font-family: 'Poppins', sans-serif; font-size: 13px;">Filter Data</button>
        </div>
    </div>
</form>

<!-- =========================================================
     4. TABEL
     ========================================================= -->
<div class="metric-card mb-4" style="padding: 0; overflow: hidden; border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="width: 100%; border-collapse: collapse;">
            <thead style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                <tr>
                    <th style="padding: 16px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</th>
                    <th style="padding: 16px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Plat Kendaraan</th>
                    <th style="padding: 16px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Driver</th>
                    <th style="padding: 16px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">BBM</th>
                    <th style="padding: 16px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Rincian</th>
                    <th class="text-end" style="padding: 16px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 16px 24px; font-size: 13.5px; color: #475569; font-weight: 500;">
                        {{ $log->tanggal_pengisian->format('d M Y') }}
                    </td>
                    <td style="padding: 16px 24px;">
                        <span style="background: var(--surface-3); color: var(--text); padding: 4px 10px; border-radius: 6px; font-size: 12.5px; font-weight: 700; letter-spacing: 0.5px;">{{ $log->vehicle->plat_nomor ?? '—' }}</span>
                    </td>
                    <td style="padding: 16px 24px; font-size: 13.5px; color: #0F172A; font-weight: 500;">
                        {{ $log->driver_name ?? '—' }}
                    </td>
                    <td style="padding: 16px 24px;">
                        <div style="font-size: 13.5px; font-weight: 600; color: #0F172A;">{{ $log->jenis_bahan_bakar }}</div>
                        <div style="font-size: 12px; color: #64748B;">{{ number_format($log->jumlah_liter, 1) }} L &middot; Rp {{ number_format($log->total_harga, 0, ',', '.') }}</div>
                    </td>
                    <td style="padding: 16px 24px;">
                        <div style="font-size: 13px; color: #0F172A;">Jarak: <b>{{ number_format($log->jarak_tempuh) }} km</b></div>
                        <div style="font-size: 12px; color: #10B981; font-weight: 500;">Avg: {{ $log->konsumsi_bbm ?? '—' }} km/l</div>
                    </td>
                    <td class="text-end" style="padding: 16px 24px;">
                        <div class="d-flex gap-2 justify-content-end">
                            @can('fuel.edit')
                            <a class="btn btn-sm" href="{{ route('fuel.edit', $log) }}" style="background: rgba(59,130,246,.1); color: #3B82F6; border: none; font-weight: 600; padding: 6px 14px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Edit</a>
                            @endcan
                            @can('fuel.delete')
                            <form class="d-inline m-0" method="post" action="{{ route('fuel.destroy', $log) }}" onsubmit="return confirm('Hapus data ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="background: rgba(225,29,72,.1); color: #E11D48; border: none; font-weight: 600; padding: 6px 14px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Hapus</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 64px 24px;">
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="width: 56px; height: 56px; background: rgba(245, 158, 11, 0.1); color: #D97706; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            </div>
                            <div style="font-size: 16px; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Data Kosong</div>
                            <div style="font-size: 13px; color: #64748B;">Belum ada catatan pengisian bahan bakar yang sesuai kriteria.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 mb-5">
    {{ $logs->links() }}
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    };

    new Chart(document.getElementById('chartFuelMonthly'), {
        type: 'bar',
        data: {
            labels: @json($monthlyChart->pluck('label')),
            datasets: [{
                label: 'Pengeluaran (Rp)',
                data: @json($monthlyChart->pluck('total')),
                backgroundColor: '#D97706',
                borderRadius: 6,
            }],
        },
        options: chartOptions,
    });

    new Chart(document.getElementById('chartConsumption'), {
        type: 'bar',
        data: {
            labels: @json($consumptionChart->pluck('plat_nomor')),
            datasets: [{
                label: 'Rata-rata km/liter',
                data: @json($consumptionChart->pluck('avg_konsumsi')),
                backgroundColor: '#3B82F6',
                borderRadius: 6,
            }],
        },
        options: { ...chartOptions, indexAxis: 'y' },
    });
});
</script>
@endpush
