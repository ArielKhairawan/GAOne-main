@extends('layouts.app')

@section('title', 'Riwayat Scan Hari Ini')
@section('page-title', 'Riwayat Scan Hari Ini')
@section('page-subtitle', now()->translatedFormat('l, d F Y'))

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Security</span>
        <h1 class="section-title">Riwayat Scan Hari Ini</h1>
        <p class="section-subtitle">Total {{ $scans->total() }} aktivitas scan.</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('sik.security.dashboard') }}">Kembali</a>
</div>

<div class="row g-4 mb-2">
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-left-color: var(--primary)">
            <div class="metric-card-accent" style="background: var(--primary)"></div>
            <div class="metric-top"><span class="metric-label">Total</span><span class="metric-dot" style="background: var(--primary)"></span></div>
            <div class="metric-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-left-color: var(--info)">
            <div class="metric-card-accent" style="background: var(--info)"></div>
            <div class="metric-top"><span class="metric-label">Keluar</span><span class="metric-dot" style="background: var(--info)"></span></div>
            <div class="metric-value">{{ $stats['keluar'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-left-color: var(--success)">
            <div class="metric-card-accent" style="background: var(--success)"></div>
            <div class="metric-top"><span class="metric-label">Kembali</span><span class="metric-dot" style="background: var(--success)"></span></div>
            <div class="metric-value">{{ $stats['kembali'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-left-color: var(--danger)">
            <div class="metric-card-accent" style="background: var(--danger)"></div>
            <div class="metric-top"><span class="metric-label">Gagal</span><span class="metric-dot" style="background: var(--danger)"></span></div>
            <div class="metric-value">{{ $stats['gagal'] }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nomor SIK</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Security</th>
                        <th>Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scans as $scan)
                    <tr>
                        <td>{{ $scan->scanned_at->format('d M Y H:i:s') }}</td>
                        <td class="fw-medium">{{ $scan->suratIzinKeluar?->nomor_sik ?? '—' }}</td>
                        <td>{{ $scan->suratIzinKeluar?->user?->name ?? '—' }}</td>
                        <td>{{ $scan->type === 'keluar' ? 'Scan Keluar' : ($scan->type === 'kembali' ? 'Scan Kembali' : '—') }}</td>
                        <td>{{ $scan->security?->name ?? '—' }}</td>
                        <td>
                            @if($scan->is_success)
                                <span class="status-badge active">Berhasil</span>
                            @else
                                <span class="status-badge inactive">{{ $scan->message }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:48px; color:var(--text-3); font-size:14px">
                            Belum ada aktivitas scan hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $scans->links() }}
</div>

@endsection
