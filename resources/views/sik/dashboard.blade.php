@extends('layouts.app')

@section('title', 'Dashboard SIK')
@section('page-title', 'Dashboard Surat Izin Keluar')
@section('page-subtitle', 'Ringkasan pengajuan Surat Izin Keluar (SIK)')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Surat Izin Keluar</span>
        <h1 class="section-title">Dashboard SIK</h1>
        <p class="section-subtitle">
            @can('sik.create')
                Ringkasan pengajuan Surat Izin Keluar Anda.
            @else
                Ringkasan seluruh pengajuan Surat Izin Keluar.
            @endcan
        </p>
    </div>
    @can('sik.create')
    <a class="btn btn-primary" href="{{ route('sik.create') }}">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Pengajuan Baru
    </a>
    @endcan
</div>

<div class="row g-4 mb-2">
    <div class="col-xl-2 col-md-4">
        <div class="metric-card project-card" style="border-left-color: var(--primary)">
            <div class="metric-card-accent" style="background: var(--primary)"></div>
            <div class="metric-top"><span class="metric-label">Total</span><span class="metric-dot" style="background: var(--primary)"></span></div>
            <div class="metric-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="metric-card project-card" style="border-left-color: #f59e0b">
            <div class="metric-card-accent" style="background: #f59e0b"></div>
            <div class="metric-top"><span class="metric-label">Pending</span><span class="metric-dot" style="background: #f59e0b"></span></div>
            <div class="metric-value">{{ $stats['pending_approval'] }}</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="metric-card project-card" style="border-left-color: var(--success)">
            <div class="metric-card-accent" style="background: var(--success)"></div>
            <div class="metric-top"><span class="metric-label">Approved</span><span class="metric-dot" style="background: var(--success)"></span></div>
            <div class="metric-value">{{ $stats['approved'] }}</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="metric-card project-card" style="border-left-color: var(--info)">
            <div class="metric-card-accent" style="background: var(--info)"></div>
            <div class="metric-top"><span class="metric-label">Sedang Keluar</span><span class="metric-dot" style="background: var(--info)"></span></div>
            <div class="metric-value">{{ $stats['sedang_keluar'] }}</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="metric-card project-card" style="border-left-color: var(--text-3)">
            <div class="metric-card-accent" style="background: var(--text-3)"></div>
            <div class="metric-top"><span class="metric-label">Completed</span><span class="metric-dot" style="background: var(--text-3)"></span></div>
            <div class="metric-value">{{ $stats['completed'] }}</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="metric-card project-card" style="border-left-color: var(--danger)">
            <div class="metric-card-accent" style="background: var(--danger)"></div>
            <div class="metric-top"><span class="metric-label">Rejected</span><span class="metric-dot" style="background: var(--danger)"></span></div>
            <div class="metric-value">{{ $stats['rejected'] }}</div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-lg-7">
        <div class="card">
            <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
                <div style="font-size:14px; font-weight:600; color:var(--text)">Pengajuan per Bulan</div>
            </div>
            <div class="card-body p-4">
                <canvas id="chartSikMonthly" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card">
            <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
                <div style="font-size:14px; font-weight:600; color:var(--text)">Status Pengajuan</div>
            </div>
            <div class="card-body p-4">
                <canvas id="chartSikStatus" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div style="padding:20px 24px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center">
        <div style="font-size:14px; font-weight:600; color:var(--text)">Pengajuan Terbaru</div>
        <a href="{{ route('sik.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nomor SIK</th>
                        <th>Nama</th>
                        <th>Jenis Izin</th>
                        <th>Jam Keluar Rencana</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $item)
                    <tr>
                        <td class="fw-medium">{{ $item->nomor_sik ?: '—' }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->jenis_izin_label }}</td>
                        <td>{{ $item->jam_keluar_rencana->format('d M Y H:i') }}</td>
                        <td><span class="status-badge sik-{{ $item->status }}">{{ $item->status_label }}</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('sik.show', $item) }}">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:48px; color:var(--text-3); font-size:14px">
                            Belum ada pengajuan SIK.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const monthlyLabels = @json(array_keys($monthlyChart));
    const monthlyData = @json(array_values($monthlyChart));

    new Chart(document.getElementById('chartSikMonthly'), {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Jumlah Pengajuan',
                data: monthlyData,
                backgroundColor: '#203A63',
                borderRadius: 6,
            }],
        },
        options: { responsive: true, plugins: { legend: { display: false } } },
    });

    new Chart(document.getElementById('chartSikStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Approved', 'Sedang Keluar', 'Completed', 'Rejected'],
            datasets: [{
                data: [
                    {{ $stats['pending_approval'] }},
                    {{ $stats['approved'] }},
                    {{ $stats['sedang_keluar'] }},
                    {{ $stats['completed'] }},
                    {{ $stats['rejected'] }},
                ],
                backgroundColor: ['#f59e0b', '#00b999', '#3B82F6', '#94a3b8', '#E11D48'],
            }],
        },
        options: { responsive: true },
    });
});
</script>
@endpush
