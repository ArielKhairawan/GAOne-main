@extends('layouts.app')

@section('title', 'Edit Pengajuan SIK')
@section('page-title', 'Edit Pengajuan SIK')
@section('page-subtitle', 'Hanya dapat diedit selama status masih Pending Approval')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Surat Izin Keluar</span>
        <h1 class="section-title">Edit Pengajuan</h1>
        <p class="section-subtitle">{{ $sik->nomor_sik ?: 'Belum ada nomor SIK (menunggu approval)' }}</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('sik.show', $sik) }}">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
        Kembali
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-4">
    <strong>Periksa kembali isian Anda:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="post" action="{{ route('sik.update', $sik) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card mb-4">
        <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
            <div style="font-size:14px; font-weight:600; color:var(--text)">Detail Pengajuan</div>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Jenis Izin <span style="color:var(--danger)">*</span></label>
                    <select class="form-select" name="jenis_izin" required>
                        @foreach($jenisIzinOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('jenis_izin', $sik->jenis_izin) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kendaraan</label>
                    <select class="form-select" name="kendaraan">
                        <option value="">— Tidak Ada —</option>
                        @foreach($kendaraanOptions as $opt)
                            <option value="{{ $opt }}" @selected(old('kendaraan', $sik->kendaraan) === $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Lampiran</label>
                    <input type="file" class="form-control" name="lampiran" accept=".jpg,.jpeg,.png,.pdf">
                    @if($sik->lampiran)
                        <small class="text-muted">File saat ini: {{ basename($sik->lampiran) }}</small>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jam Keluar Rencana <span style="color:var(--danger)">*</span></label>
                    <input type="datetime-local" class="form-control" name="jam_keluar_rencana" value="{{ old('jam_keluar_rencana', $sik->jam_keluar_rencana->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jam Kembali Rencana <span style="color:var(--danger)">*</span></label>
                    <input type="datetime-local" class="form-control" name="jam_kembali_rencana" value="{{ old('jam_kembali_rencana', $sik->jam_kembali_rencana->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Keperluan <span style="color:var(--danger)">*</span></label>
                    <textarea class="form-control" name="keperluan" rows="3" required>{{ old('keperluan', $sik->keperluan) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea class="form-control" name="catatan" rows="2">{{ old('catatan', $sik->catatan) }}</textarea>
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('sik.show', $sik) }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>

@endsection
