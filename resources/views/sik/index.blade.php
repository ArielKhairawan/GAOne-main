@extends('layouts.app')

@section('title', 'Riwayat SIK')
@section('page-title', 'Riwayat Surat Izin Keluar')
@section('page-subtitle', 'Daftar seluruh pengajuan Surat Izin Keluar')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Surat Izin Keluar</span>
        <h1 class="section-title">Riwayat SIK</h1>
        <p class="section-subtitle">Total {{ $items->total() }} pengajuan.</p>
    </div>
    @can('sik.create')
    <a class="btn btn-primary" href="{{ route('sik.create') }}">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Pengajuan Baru
    </a>
    @endcan
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
                <label class="form-label">Jenis Izin</label>
                <select name="jenis_izin" class="form-select">
                    <option value="">— Semua —</option>
                    @foreach($jenisIzinOptions as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['jenis_izin'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">— Semua —</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary" style="width:100%; justify-content:center; margin-top:22px">Filter</button>
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
                            <div class="d-flex gap-2" style="justify-content:flex-end">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('sik.show', $item) }}">Lihat</a>
                                @if($item->status === 'pending_approval' && $item->user_id === auth()->id())
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('sik.edit', $item) }}">Edit</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:48px; color:var(--text-3); font-size:14px">
                            Belum ada pengajuan SIK.
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
