@extends('layouts.app')

@section('title', 'Detail SIK')
@section('page-title', 'Detail Surat Izin Keluar')
@section('page-subtitle', $sik->nomor_sik ?: 'Belum ada nomor SIK (menunggu approval)')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Surat Izin Keluar</span>
        <h1 class="section-title">{{ $sik->nomor_sik ?: 'Pengajuan SIK' }}</h1>
        <p class="section-subtitle">
            <span class="status-badge sik-{{ $sik->status }}">{{ $sik->status_label }}</span>
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('sik.index') }}">Kembali</a>
        @if(in_array($sik->status, ['approved', 'sedang_keluar', 'completed']))
        <a class="btn btn-outline-primary" target="_blank" href="{{ route('sik.pdf', $sik) }}">Download / Cetak PDF</a>
        @endif
        @if($sik->status === 'pending_approval' && $sik->user_id === auth()->id())
        <a class="btn btn-outline-secondary" href="{{ route('sik.edit', $sik) }}">Edit</a>
        @endif
        @if(in_array($sik->status, ['pending_approval', 'approved']) && ($sik->user_id === auth()->id() || auth()->user()->hasAnyRole(['Admin', 'GA Staff'])))
        <form method="post" action="{{ route('sik.cancel', $sik) }}" onsubmit="return confirm('Batalkan pengajuan ini?')">
            @csrf
            <button class="btn btn-outline-danger">Batalkan</button>
        </form>
        @endif
    </div>
</div>

@if(session('status'))
<div class="alert alert-success mb-4">{{ session('status') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">

        <div class="card mb-4">
            <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
                <div style="font-size:14px; font-weight:600; color:var(--text)">Data Pemohon</div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Nama</label><div class="fw-medium">{{ $sik->user->name }}</div></div>
                    <div class="col-md-4"><label class="form-label">Nomor Karyawan</label><div class="fw-medium">{{ $sik->user->employee_number_display }}</div></div>
                    <div class="col-md-4"><label class="form-label">Departemen</label><div class="fw-medium">{{ $sik->department ?: '—' }}</div></div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
                <div style="font-size:14px; font-weight:600; color:var(--text)">Detail Pengajuan</div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Jenis Izin</label><div class="fw-medium">{{ $sik->jenis_izin_label }}</div></div>
                    <div class="col-md-4"><label class="form-label">Kendaraan</label><div class="fw-medium">{{ $sik->kendaraan ?: '—' }}</div></div>
                    <div class="col-md-4"><label class="form-label">Lampiran</label>
                        <div class="fw-medium">
                            @if($sik->lampiran)
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($sik->lampiran) }}" target="_blank">Lihat Lampiran</a>
                            @else — @endif
                        </div>
                    </div>
                    <div class="col-md-6"><label class="form-label">Jam Keluar Rencana</label><div class="fw-medium">{{ $sik->jam_keluar_rencana->format('d M Y H:i') }}</div></div>
                    <div class="col-md-6"><label class="form-label">Jam Kembali Rencana</label><div class="fw-medium">{{ $sik->jam_kembali_rencana->format('d M Y H:i') }}</div></div>
                    <div class="col-12"><label class="form-label">Keperluan</label><div class="fw-medium">{{ $sik->keperluan }}</div></div>
                    @if($sik->catatan)
                    <div class="col-12"><label class="form-label">Catatan</label><div class="fw-medium">{{ $sik->catatan }}</div></div>
                    @endif
                </div>
            </div>
        </div>

        @if($sik->approved_at)
        <div class="card mb-4">
            <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
                <div style="font-size:14px; font-weight:600; color:var(--text)">Approval</div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4"><label class="form-label">Manager</label><div class="fw-medium">{{ $sik->manager?->name }}</div></div>
                    <div class="col-md-4"><label class="form-label">Tanggal Approval</label><div class="fw-medium">{{ $sik->approved_at->format('d M Y H:i') }}</div></div>
                    <div class="col-md-4"><label class="form-label">Status</label><div class="fw-medium">{{ $sik->status_label }}</div></div>
                    @if($sik->approval_note)
                    <div class="col-12"><label class="form-label">Catatan Approval</label><div class="fw-medium">{{ $sik->approval_note }}</div></div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($sik->jam_keluar_aktual)
        <div class="card mb-4">
            <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
                <div style="font-size:14px; font-weight:600; color:var(--text)">Proses Keluar &amp; Kembali</div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-3"><label class="form-label">Jam Keluar Aktual</label><div class="fw-medium">{{ $sik->jam_keluar_aktual->format('d M Y H:i') }}</div></div>
                    <div class="col-md-3"><label class="form-label">Jam Masuk Aktual</label><div class="fw-medium">{{ $sik->jam_kembali_aktual?->format('d M Y H:i') ?? '—' }}</div></div>
                    <div class="col-md-3"><label class="form-label">Durasi di Luar</label><div class="fw-medium">{{ $sik->durasi_di_luar ?? '—' }}</div></div>
                    <div class="col-md-3"><label class="form-label">Status</label><div class="fw-medium">{{ $sik->status_label }}</div></div>
                    <div class="col-md-6"><label class="form-label">Security Keluar</label><div class="fw-medium">{{ $sik->securityOut?->name ?? '—' }}</div></div>
                    <div class="col-md-6"><label class="form-label">Security Masuk</label><div class="fw-medium">{{ $sik->securityIn?->name ?? '—' }}</div></div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
                <div style="font-size:14px; font-weight:600; color:var(--text)">Riwayat Scan</div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr><th>Waktu</th><th>Tipe</th><th>Security</th><th>Hasil</th></tr>
                        </thead>
                        <tbody>
                            @forelse($sik->scans as $scan)
                            <tr>
                                <td>{{ $scan->scanned_at->format('d M Y H:i:s') }}</td>
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
                            <tr><td colspan="4" style="text-align:center; padding:24px; color:var(--text-3)">Belum ada riwayat scan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>

    <div class="col-lg-4">
        <div class="card">
            <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
                <div style="font-size:14px; font-weight:600; color:var(--text)">QR Code</div>
            </div>
            <div class="card-body p-4 text-center">
                @if($qrSvg)
                    <div class="sik-qr-box">
                        {!! $qrSvg !!}
                        @if($sik->status === 'completed')
                            <div class="small text-muted">QR ini sudah tidak dapat digunakan lagi (SIK selesai).</div>
                        @else
                            <div class="small text-muted">Tunjukkan QR ini ke Security saat keluar/kembali.</div>
                        @endif
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <a class="btn btn-sm btn-outline-primary" target="_blank" href="{{ route('sik.pdf', $sik) }}">Unduh (PDF)</a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">Cetak</button>
                    </div>
                @else
                    <p class="text-muted mb-0">
                        QR Code akan tersedia setelah pengajuan <strong>Approved</strong>.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
