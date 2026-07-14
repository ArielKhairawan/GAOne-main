@extends('layouts.app')

@section('title', 'Laporan SIK')
@section('page-title', 'Laporan Surat Izin Keluar')
@section('page-subtitle', 'Statistik dan laporan seluruh pengajuan SIK')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Surat Izin Keluar</span>
        <h1 class="section-title">Laporan SIK</h1>
        <p class="section-subtitle">Total {{ $items->total() }} data.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        @can('sik.export')
        <a class="btn btn-outline-danger" href="{{ route('sik.laporan.export.pdf', $filters) }}">Export PDF</a>
        <a class="btn btn-outline-success" href="{{ route('sik.laporan.export.excel', $filters) }}">Export Excel</a>
        @endcan
    </div>
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

<form method="get" class="card mb-4">
    <div class="card-body p-3">
        <div class="row g-3 align-items-center">
            <div class="col-md-2">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Departemen</label>
                <select name="department" class="form-select">
                    <option value="">— Semua —</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" @selected(($filters['department'] ?? '') === $dept)>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">— Semua —</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Jenis Izin</label>
                <select name="jenis_izin" class="form-select">
                    <option value="">— Semua —</option>
                    @foreach($jenisIzinOptions as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['jenis_izin'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Karyawan</label>
                <select name="employee_id" class="form-select">
                    <option value="">— Semua —</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" @selected((string) ($filters['employee_id'] ?? '') === (string) $emp->id)>{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 text-end">
                <button class="btn btn-outline-primary">Filter</button>
            </div>
        </div>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nomor SIK</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Jenis Izin</th>
                        <th>Jam Keluar Rencana</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="fw-medium">{{ $item->nomor_sik ?: '—' }}</td>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ $item->department ?: '—' }}</td>
                        <td>{{ $item->jenis_izin_label }}</td>
                        <td>{{ $item->jam_keluar_rencana->format('d M Y H:i') }}</td>
                        <td><span class="status-badge sik-{{ $item->status }}">{{ $item->status_label }}</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('sik.show', $item) }}">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:48px; color:var(--text-3); font-size:14px">
                            Tidak ada data untuk filter ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $items->links() }}
</div>

@endsection
