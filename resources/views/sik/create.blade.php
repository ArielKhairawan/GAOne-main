@extends('layouts.app')

@section('title', 'Pengajuan SIK')
@section('page-title', 'Pengajuan Surat Izin Keluar')
@section('page-subtitle', 'Nama, Nomor Karyawan, dan Departemen diambil otomatis dari akun Anda')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Surat Izin Keluar</span>
        <h1 class="section-title">Pengajuan Baru</h1>
        <p class="section-subtitle">Lengkapi detail keperluan keluar. Pengajuan akan dikirim ke Manager departemen Anda.</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('sik.index') }}">
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

<div class="card mb-4">
    <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
        <div style="font-size:14px; font-weight:600; color:var(--text)">Data Pemohon (Otomatis)</div>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nomor Karyawan</label>
                <input type="text" class="form-control" value="{{ auth()->user()->employee_number_display }}" disabled>
            </div>
            <div class="col-md-4">
                <label class="form-label">Departemen</label>
                <input type="text" class="form-control" value="{{ auth()->user()->department ?? '—' }}" disabled>
            </div>
        </div>
    </div>
</div>

<form method="post" action="{{ route('sik.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="card mb-4">
        <div style="padding:20px 24px; border-bottom:1px solid var(--border)">
            <div style="font-size:14px; font-weight:600; color:var(--text)">Detail Pengajuan</div>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Jenis Izin <span style="color:var(--danger)">*</span></label>
                    <select class="form-select" name="jenis_izin" required>
                        <option value="">— Pilih —</option>
                        @foreach($jenisIzinOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('jenis_izin') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kendaraan</label>
                    <select class="form-select" name="kendaraan">
                        <option value="">— Tidak Ada —</option>
                        @foreach($kendaraanOptions as $opt)
                            <option value="{{ $opt }}" @selected(old('kendaraan') === $opt)>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Lampiran (opsional)</label>
                    <input type="file" class="form-control" name="lampiran" accept=".jpg,.jpeg,.png,.pdf">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jam Keluar Rencana <span style="color:var(--danger)">*</span></label>
                    <input type="datetime-local" class="form-control" name="jam_keluar_rencana" value="{{ old('jam_keluar_rencana', now()->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jam Kembali Rencana <span style="color:var(--danger)">*</span></label>
                    <input type="datetime-local" class="form-control" name="jam_kembali_rencana" value="{{ old('jam_kembali_rencana', now()->addHours(2)->format('Y-m-d\TH:i')) }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Keperluan <span style="color:var(--danger)">*</span></label>
                    <textarea class="form-control" name="keperluan" rows="3" required placeholder="Jelaskan keperluan keluar">{{ old('keperluan') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea class="form-control" name="catatan" rows="2" placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('sik.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
    </div>
</form>

@endsection
