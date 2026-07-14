@extends('layouts.app')

@section('title', ($inspection->exists ? 'Edit' : 'Tambah') . ' Inspeksi WC')
@section('page-title', ($inspection->exists ? 'Edit' : 'Tambah') . ' Inspeksi WC')
@section('page-subtitle', 'Lengkapi seluruh data inspeksi di bawah ini dengan akurat untuk menjaga standar kebersihan.')

@php
    $existingItems = $inspection->exists ? $inspection->items->pluck('status', 'item_name') : collect();
@endphp

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    <a href="{{ route('toilet.index') }}" class="btn btn-sm" style="background: var(--surface-3); color: var(--text); border: 1px solid var(--border); font-weight: 600; padding: 8px 16px; border-radius: 8px; display: flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
        Kembali ke Daftar
    </a>
</div>

@if($errors->any())
<div class="alert mb-4" style="background: rgba(225,29,72,.1); border: 1px solid rgba(225,29,72,.2); border-radius: 12px; color: #E11D48; padding: 16px 20px;">
    <strong style="font-size: 14px;">Ada beberapa isian yang kurang tepat:</strong>
    <ul class="mb-0 mt-2" style="font-size: 13px;">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="post" action="{{ $inspection->exists ? route('toilet.update', $inspection) : route('toilet.store') }}" enctype="multipart/form-data">
    @csrf
    @if($inspection->exists) @method('PUT') @endif

    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Informasi Utama</div>
        </div>

        <div style="padding: 32px 24px;">
            <div class="row g-4">
                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Tanggal <span style="color: #E11D48;">*</span></label>
                    <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal', optional($inspection->tanggal)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Jam <span style="color: #E11D48;">*</span></label>
                    <input type="time" class="form-control" name="jam" value="{{ old('jam', $inspection->jam ?? now()->format('H:i')) }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Lokasi Toilet <span style="color: #E11D48;">*</span></label>
                    <select class="form-select" name="lokasi" id="lokasiSelect" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" @selected(old('lokasi', $inspection->lokasi) === $loc)>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4" id="lokasiDetailWrap">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Detail Lokasi Spesifik</label>
                    <input type="text" class="form-control" name="lokasi_detail" value="{{ old('lokasi_detail', $inspection->lokasi_detail) }}" placeholder="Cth: Lantai 2 - Dekat Lobby" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Petugas Pemeriksa <span style="color: #E11D48;">*</span></label>
                    <select class="form-select" name="petugas_id" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                        <option value="">— Pilih Petugas —</option>
                        @foreach($petugasOptions as $p)
                            <option value="{{ $p->id }}" @selected((int) old('petugas_id', $inspection->petugas_id ?? auth()->id()) === $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Status Akhir <span style="color: #E11D48;">*</span></label>
                    <select class="form-select" name="status" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $inspection->status ?? 'bersih') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Bukti Foto <span style="color: #E11D48;">*</span> <small style="font-weight: 400; color: #64748B;">(Maks 5MB)</small></label>
                    <input type="file" class="form-control" name="foto" accept="image/jpeg,image/png,image/webp" {{ $inspection->exists ? '' : 'required' }} style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 7px 14px; border-radius: 8px;">
                    @if($inspection->foto)
                        <div style="font-size: 12px; color: #64748B; margin-top: 6px;">Foto lama telah tersimpan. Unggah foto baru jika ingin menggantinya.</div>
                    @endif
                </div>

                <div class="col-md-12">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Catatan Tambahan</label>
                    <textarea class="form-control" name="catatan" rows="3" placeholder="Tuliskan temuan kerusakan atau instruksi tambahan (opsional)..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 12px 14px; border-radius: 8px;">{{ old('catatan', $inspection->catatan) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Checklist Kebersihan & Fasilitas</div>
        </div>
        <div style="padding: 32px 24px;">
            <div class="row g-4">
                @foreach($checklistItems as $item)
                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">{{ $item }}</label>
                    <select class="form-select" name="items[{{ $item }}]" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                        @foreach($itemStatuses as $value => $label)
                            <option value="{{ $value }}" @selected(old("items.$item", $existingItems->get($item, 'baik')) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Tanda Tangan Petugas</div>
        </div>
        <div style="padding: 28px 24px;">
            <canvas id="signaturePad" width="500" height="160" style="border: 1px dashed #CBD5E1; border-radius: 12px; width: 100%; max-width: 500px; touch-action: none; cursor: crosshair; background: #F8FAFC;"></canvas>
            <input type="hidden" name="tanda_tangan" id="tandaTanganInput" value="{{ old('tanda_tangan', $inspection->tanda_tangan) }}">
            <div class="mt-3">
                <button type="button" id="clearSignature" class="btn btn-sm" style="background: #F1F5F9; color: #475569; border: none; font-weight: 600; font-family: 'Poppins', sans-serif; padding: 8px 16px; border-radius: 8px; font-size: 12.5px;">Hapus & Ulangi Tanda Tangan</button>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3 mt-2 mb-5">
        <button type="submit" class="btn" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 32px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);">
            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
            Simpan Laporan
        </button>
        <a class="btn" href="{{ route('toilet.index') }}" style="background: transparent; color: #64748B; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 24px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Batal</a>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Logika show/hide detail lokasi
    var lokasiSelect = document.getElementById('lokasiSelect');
    var lokasiDetailWrap = document.getElementById('lokasiDetailWrap');

    function toggleLokasiDetail() {
        lokasiDetailWrap.style.display = lokasiSelect.value === 'Lokasi Lainnya' ? 'block' : 'none';
    }
    lokasiSelect.addEventListener('change', toggleLokasiDetail);
    toggleLokasiDetail();

    // Canvas Tanda Tangan
    var canvas = document.getElementById('signaturePad');
    var ctx = canvas.getContext('2d');
    var input = document.getElementById('tandaTanganInput');
    var drawing = false;

    ctx.lineWidth = 2.5;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#0F172A';

    if (input.value) {
        var img = new Image();
        img.onload = function () { ctx.drawImage(img, 0, 0, canvas.width, canvas.height); };
        img.src = input.value;
    }

    function getPos(e) {
        var rect = canvas.getBoundingClientRect();
        var scaleX = canvas.width / rect.width;
        var scaleY = canvas.height / rect.height;
        var point = e.touches ? e.touches[0] : e;
        return {
            x: (point.clientX - rect.left) * scaleX,
            y: (point.clientY - rect.top) * scaleY,
        };
    }

    function start(e) {
        drawing = true;
        var pos = getPos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
        e.preventDefault();
    }

    function move(e) {
        if (!drawing) return;
        var pos = getPos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        e.preventDefault();
    }

    function end() {
        if (!drawing) return;
        drawing = false;
        input.value = canvas.toDataURL('image/png');
    }

    canvas.addEventListener('mousedown', start);
    canvas.addEventListener('mousemove', move);
    canvas.addEventListener('mouseup', end);
    canvas.addEventListener('mouseleave', end);
    canvas.addEventListener('touchstart', start, {passive: false});
    canvas.addEventListener('touchmove', move, {passive: false});
    canvas.addEventListener('touchend', end);

    document.getElementById('clearSignature').addEventListener('click', function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        input.value = '';
    });
});
</script>
@endpush
