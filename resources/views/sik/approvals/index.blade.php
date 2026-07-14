@extends('layouts.app')

@section('title', 'Approval SIK')
@section('page-title', 'Approval Surat Izin Keluar')
@section('page-subtitle', 'Daftar pengajuan SIK yang menunggu persetujuan Anda')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Surat Izin Keluar</span>
        <h1 class="section-title">Daftar Approval</h1>
        <p class="section-subtitle">Total {{ $items->total() }} pengajuan menunggu persetujuan.</p>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Jenis Izin</th>
                        <th>Keperluan</th>
                        <th>Jam Keluar Rencana</th>
                        <th>Diajukan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="fw-medium">{{ $item->user->name }}</td>
                        <td>{{ $item->department ?: '—' }}</td>
                        <td>{{ $item->jenis_izin_label }}</td>
                        <td style="max-width:260px; white-space:normal">{{ \Illuminate\Support\Str::limit($item->keperluan, 60) }}</td>
                        <td>{{ $item->jam_keluar_rencana->format('d M Y H:i') }}</td>
                        <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-primary" href="{{ route('sik.approvals.show', $item) }}">Proses</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:48px; color:var(--text-3); font-size:14px">
                            Tidak ada pengajuan yang menunggu persetujuan.
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
