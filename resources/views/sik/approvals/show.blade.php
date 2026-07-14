@extends('layouts.app')

@section('title', 'Proses Approval SIK')
@section('page-title', 'Proses Approval SIK')
@section('page-subtitle', 'Tinjau detail pengajuan sebelum menyetujui atau menolak')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Surat Izin Keluar</span>
        <h1 class="section-title">Pengajuan {{ $sik->user->name }}</h1>
        <p class="section-subtitle"><span class="status-badge sik-{{ $sik->status }}">{{ $sik->status_label }}</span></p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('sik.approvals.index') }}">Kembali</a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-4">
    <ul class="mb-0">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="card mb-4">
    <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
        <div style="font-size:14px; font-weight:600; color:var(--text)">Detail Pengajuan</div>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label">Nama</label><div class="fw-medium">{{ $sik->user->name }}</div></div>
            <div class="col-md-4"><label class="form-label">Nomor Karyawan</label><div class="fw-medium">{{ $sik->user->employee_number_display }}</div></div>
            <div class="col-md-4"><label class="form-label">Departemen</label><div class="fw-medium">{{ $sik->department ?: '—' }}</div></div>
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
            <div class="col-12"><label class="form-label">Catatan Pemohon</label><div class="fw-medium">{{ $sik->catatan }}</div></div>
            @endif
        </div>
    </div>
</div>

@if($sik->status === 'pending_approval')
<div class="card">
    <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
        <div style="font-size:14px; font-weight:600; color:var(--text)">Keputusan</div>
    </div>
    <div class="card-body p-4">
        <form method="post" action="{{ route('sik.approvals.process', $sik) }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Catatan (wajib jika menolak)</label>
                <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan approval / alasan penolakan">{{ old('catatan') }}</textarea>
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <button type="submit" name="action" value="reject" class="btn btn-outline-danger">Reject</button>
                <button type="submit" name="action" value="approve" class="btn btn-primary">Approve</button>
            </div>
        </form>
    </div>
</div>
@else
<div class="alert alert-secondary mb-0">Pengajuan ini sudah diproses sebelumnya ({{ $sik->status_label }}).</div>
@endif

@endsection
