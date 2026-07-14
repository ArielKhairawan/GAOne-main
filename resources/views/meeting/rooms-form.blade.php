@extends('layouts.app')

@section('title', ($room->exists ? 'Edit' : 'Tambah') . ' Ruangan')
@section('page-title', ($room->exists ? 'Edit' : 'Tambah') . ' Ruangan Meeting')
@section('page-subtitle', 'Lengkapi deskripsi, kapasitas, serta fasilitas standar yang disediakan pada ruang pertemuan.')

@section('content')

<!-- Header Form -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('meeting.rooms.index') }}" style="background: var(--surface-3); color: var(--text); border: 1px solid var(--border); font-weight: 600; padding: 8px 16px; border-radius: 8px; display: flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
        Kembali ke Daftar
    </a>
</div>

<!-- Notifikasi Validasi Error -->
@if($errors->any())
<div class="alert mb-4" style="background: rgba(225,29,72,.1); border: 1px solid rgba(225,29,72,.2); border-radius: 12px; color: #E11D48; padding: 16px 20px;">
    <strong style="font-size: 14px;">Periksa kembali isian Anda:</strong>
    <ul class="mb-0 mt-2" style="font-size: 13px;">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="post" action="{{ $room->exists ? route('meeting.rooms.update', $room) : route('meeting.rooms.store') }}" enctype="multipart/form-data">
    @csrf
    @if($room->exists) @method('PUT') @endif

    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Detail Konfigurasi Ruangan</div>
        </div>

        <div style="padding: 32px 24px;">
            <div class="row g-4">

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Kode Ruangan <span style="color: #E11D48;">*</span></label>
                    <input type="text" class="form-control" name="kode_ruangan" value="{{ old('kode_ruangan', $room->kode_ruangan) }}" placeholder="Cth: RM-101" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-8">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Nama Ruangan <span style="color: #E11D48;">*</span></label>
                    <input type="text" class="form-control" name="nama_ruangan" value="{{ old('nama_ruangan', $room->nama_ruangan) }}" placeholder="Cth: Ruang Aula Ki Hajar" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Lokasi Ruangan</label>
                    <input type="text" class="form-control" name="lokasi" value="{{ old('lokasi', $room->lokasi) }}" placeholder="Cth: Gedung A - Lantai 3" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                <div class="col-md-3">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Kapasitas (Orang) <span style="color: #E11D48;">*</span></label>
                    <input type="number" min="1" class="form-control" name="kapasitas" value="{{ old('kapasitas', $room->kapasitas) }}" placeholder="Cth: 20" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-3">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Status Ruangan <span style="color: #E11D48;">*</span></label>
                    <select class="form-select" name="status" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                        @foreach(config('monitoring.meeting_room_statuses') as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $room->status ?? 'tersedia') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Foto Ruangan Meeting</label>
                    <input type="file" class="form-control" name="foto" accept="image/jpeg,image/png,image/webp" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 7px 14px; border-radius: 8px;">
                    @if($room->foto)
                        <div style="font-size: 12px; color: #64748B; margin-top: 6px;">Foto ruangan telah tersedia. Unggah file baru untuk menggantinya.</div>
                    @endif
                </div>

                <div class="col-12">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Deskripsi Ruangan</label>
                    <textarea class="form-control" name="deskripsi" rows="2" placeholder="Tulis deskripsi singkat mengenai kegunaan atau detail lainnya..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 12px 14px; border-radius: 8px;">{{ old('deskripsi', $room->deskripsi) }}</textarea>
                </div>

                <div class="col-12">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 12px; display: block;">Fasilitas Ruangan Tersedia</label>
                    <div class="row g-3">
                        @foreach(config('monitoring.meeting_room_facilities') as $facility)
                        <div class="col-md-3 col-6">
                            <div class="form-check" style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 12px 12px 12px 32px; border-radius: 8px; cursor: pointer;">
                                <input type="checkbox" class="form-check-input" name="fasilitas[]" id="fac-{{ $loop->index }}" value="{{ $facility }}" @checked(in_array($facility, old('fasilitas', $room->fasilitas ?? []))) style="cursor: pointer;">
                                <label class="form-check-label" for="fac-{{ $loop->index }}" style="font-size: 13px; font-weight: 600; color: #334155; cursor: pointer;">{{ $facility }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Tombol Aksi Simpan & Batal -->
    <div class="d-flex gap-3 mt-2 mb-5">
        <button type="submit" class="btn" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 32px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);">
            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
            Simpan Ruangan
        </button>
        <a class="btn" href="{{ route('meeting.rooms.index') }}" style="background: transparent; color: #64748B; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 24px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Batal</a>
    </div>
</form>

@endsection
