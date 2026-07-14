@extends('layouts.app')

@section('title', 'Pengajuan SIK')

@section('content')

<!-- Header Form (Tombol Kembali di Sebelah Kanan) -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('sik.index') }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Kembali
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-4" style="border-radius: 12px; font-family: 'Poppins', sans-serif; font-size: 13.5px;">
    <strong>Periksa kembali isian Anda:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Data Pemohon (Otomatis) -->
<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 28px; font-family: 'Poppins', sans-serif;">
    <div style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 24px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px;">
        Data Pemohon (Otomatis)
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #64748B;">Nama Pemohon</label>
            <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px; color: #64748B; font-weight: 600;">
        </div>
        <div class="col-md-4">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #64748B;">Nomor Karyawan</label>
            <input type="text" class="form-control" value="{{ auth()->user()->employee_number_display }}" disabled style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px; color: #64748B; font-weight: 600;">
        </div>
        <div class="col-md-4">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #64748B;">Departemen</label>
            <input type="text" class="form-control" value="{{ auth()->user()->department ?? '—' }}" disabled style="background: #F8FAFC; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px; color: #64748B; font-weight: 600;">
        </div>
    </div>
</div>

<form method="post" action="{{ route('sik.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- Detail Isian Pengajuan -->
    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 28px; font-family: 'Poppins', sans-serif;">
        <div style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 24px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px;">
            Detail Pengajuan SIK
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Jenis Izin <span style="color:#EF4444">*</span></label>
                <select class="form-select" name="jenis_izin" required style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                    <option value="">— Pilih —</option>
                    @foreach($jenisIzinOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('jenis_izin') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Kendaraan</label>
                <select class="form-select" name="kendaraan" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                    <option value="">— Tidak Ada —</option>
                    @foreach($kendaraanOptions as $opt)
                        <option value="{{ $opt }}" @selected(old('kendaraan') === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Lampiran <span style="color: #94A3B8; font-weight: 400;">(Opsional)</span></label>
                <input type="file" class="form-control" name="lampiran" accept=".jpg,.jpeg,.png,.pdf" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px; line-height: 26px;">
            </div>

            <div class="col-md-6">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Jam Keluar Rencana <span style="color:#EF4444">*</span></label>
                <input type="datetime-local" class="form-control" name="jam_keluar_rencana" value="{{ old('jam_keluar_rencana', now()->format('Y-m-d\TH:i')) }}" required style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
            </div>

            <div class="col-md-6">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Jam Kembali Rencana <span style="color:#EF4444">*</span></label>
                <input type="datetime-local" class="form-control" name="jam_kembali_rencana" value="{{ old('jam_kembali_rencana', now()->addHours(2)->format('Y-m-d\TH:i')) }}" required style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
            </div>

            <div class="col-12">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Keperluan <span style="color:#EF4444">*</span></label>
                <textarea class="form-control" name="keperluan" rows="3" required placeholder="Jelaskan keperluan izin keluar secara detail" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; padding: 12px; resize: vertical;">{{ old('keperluan') }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Catatan</label>
                <textarea class="form-control" name="catatan" rows="2" placeholder="Catatan tambahan (opsional)" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; padding: 12px; resize: vertical;">{{ old('catatan') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi Bawah -->
    <div class="d-flex align-items-center gap-2" style="font-family: 'Poppins', sans-serif;">
        <button type="submit" class="btn btn-sm" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 12px 28px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            Kirim Pengajuan
        </button>
        <a class="btn btn-sm" href="{{ route('sik.index') }}" style="background: #F1F5F9; color: #475569; border: none; font-weight: 600; padding: 12px 24px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;">
            Batal
        </a>
    </div>
</form>

@endsection
