@extends('layouts.app')

@section('title', 'Buat Booking Ruang Meeting')
@section('page-title', 'Buat Booking Ruang Meeting')
@section('page-subtitle', 'Sistem akan memeriksa ketersediaan ruangan secara otomatis sebelum diajukan.')

@section('content')

<!-- Header Form -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('meeting.bookings.index') }}" style="background: var(--surface-3); color: var(--text); border: 1px solid var(--border); font-weight: 600; padding: 8px 16px; border-radius: 8px; display: flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
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

<form method="post" action="{{ route('meeting.bookings.store') }}">
    @csrf

    <!-- Section 1: Detail Booking -->
    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Detail Jadwal Booking</div>
        </div>
        <div style="padding: 32px 24px;">
            <div class="row g-4">
                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Ruangan <span style="color: #E11D48;">*</span></label>
                    <select class="form-select" name="meeting_room_id" id="roomSelect" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                        <option value="">— Pilih Ruangan —</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" data-kapasitas="{{ $room->kapasitas }}" @selected(old('meeting_room_id') == $room->id)>
                                {{ $room->nama_ruangan }} (kapasitas {{ $room->kapasitas }} orang)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Nama Kegiatan / Rapat <span style="color: #E11D48;">*</span></label>
                    <input type="text" class="form-control" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" placeholder="Cth: Koordinasi Bulanan Divisi IT" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Tanggal Pertemuan <span style="color: #E11D48;">*</span></label>
                    <input type="date" class="form-control" name="tanggal" id="tanggalInput" min="{{ now()->format('Y-m-d') }}" value="{{ old('tanggal') }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Jam Mulai <span style="color: #E11D48;">*</span></label>
                    <input type="time" class="form-control" name="jam_mulai" id="jamMulaiInput" value="{{ old('jam_mulai') }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Jam Selesai <span style="color: #E11D48;">*</span></label>
                    <input type="time" class="form-control" name="jam_selesai" id="jamSelesaiInput" value="{{ old('jam_selesai') }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <!-- Notifikasi Real-time Bentrok Jadwal -->
                <div class="col-12" id="availabilityNotice"></div>

                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Departemen Pengaju</label>
                    <input type="text" class="form-control" name="departemen" value="{{ old('departemen', auth()->user()->department) }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Jumlah Peserta Rapat <span style="color: #E11D48;">*</span></label>
                    <input type="number" min="1" class="form-control" name="jumlah_peserta" id="pesertaInput" value="{{ old('jumlah_peserta') }}" placeholder="Cth: 15" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-12">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Catatan Khusus Ruangan</label>
                    <textarea class="form-control" name="catatan" rows="2" placeholder="Tuliskan konfigurasi meja atau peralatan tambahan jika ada..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 12px 14px; border-radius: 8px;">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Layanan Konsumsi -->
    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 24px;">
            <div class="form-check form-switch" style="padding-left: 2.5em;">
                <input type="checkbox" class="form-check-input" name="butuh_konsumsi" id="butuhKonsumsi" value="1" @checked(old('butuh_konsumsi')) style="width: 2.2em; height: 1.2em; cursor: pointer;">
                <label class="form-check-label" for="butuhKonsumsi" style="font-size: 14px; font-weight: 700; color: #0F172A; cursor: pointer; user-select: none; margin-left: 10px;">Butuh Layanan Konsumsi?</label>
            </div>

            <!-- Bagian detail konsumsi yang meluncur muncul -->
            <div id="consumptionFields" style="display:none; margin-top:24px; border-top: 1px solid #E2E8F0; padding-top: 24px;">
                <div class="row g-4">
                    <div class="col-12">
                        <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 12px; display: block;">Pilihan Jenis Konsumsi</label>
                        <div class="row g-3">
                            @foreach(config('monitoring.consumption_types') as $type)
                            <div class="col-md-3 col-6">
                                <div class="form-check" style="background: #F8FAFC; border: 1px solid #E2E8F0; padding: 12px 12px 12px 32px; border-radius: 8px; cursor: pointer;">
                                    <input type="checkbox" class="form-check-input" name="jenis_konsumsi[]" id="jk-{{ $loop->index }}" value="{{ $type }}" @checked(in_array($type, old('jenis_konsumsi', []))) style="cursor: pointer;">
                                    <label class="form-check-label" for="jk-{{ $loop->index }}" style="font-size: 13px; font-weight: 600; color: #334155; cursor: pointer;">{{ $type }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12">
                        <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Detail & Jumlah Konsumsi</label>
                        <textarea class="form-control" name="detail_konsumsi" rows="2" placeholder="Cth: Nasi Kotak Ayam Bakar (15 Porsi), Air Mineral Gelas (1 Dus)" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 12px 14px; border-radius: 8px;">{{ old('detail_konsumsi') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi Simpan & Batal -->
    <div class="d-flex gap-3 mt-2 mb-5">
        <button type="submit" class="btn" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 32px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);">
            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
            Ajukan Booking
        </button>
        <a class="btn" href="{{ route('meeting.bookings.index') }}" style="background: transparent; color: #64748B; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 24px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Batal</a>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var butuhKonsumsi = document.getElementById('butuhKonsumsi');
    var consumptionFields = document.getElementById('consumptionFields');

    function toggleConsumption() {
        consumptionFields.style.display = butuhKonsumsi.checked ? 'block' : 'none';
    }
    butuhKonsumsi.addEventListener('change', toggleConsumption);
    toggleConsumption();

    var roomSelect = document.getElementById('roomSelect');
    var tanggalInput = document.getElementById('tanggalInput');
    var jamMulaiInput = document.getElementById('jamMulaiInput');
    var jamSelesaiInput = document.getElementById('jamSelesaiInput');
    var notice = document.getElementById('availabilityNotice');

    function checkAvailability() {
        var roomId = roomSelect.value, tanggal = tanggalInput.value, jamMulai = jamMulaiInput.value, jamSelesai = jamSelesaiInput.value;
        notice.innerHTML = '';
        if (! roomId || ! tanggal || ! jamMulai || ! jamSelesai || jamSelesai <= jamMulai) {
            return;
        }
        var url = '{{ route('meeting.bookings.check-availability') }}?meeting_room_id=' + roomId + '&tanggal=' + tanggal + '&jam_mulai=' + jamMulai + '&jam_selesai=' + jamSelesai;
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.available) {
                    notice.innerHTML = '<div class="alert mb-2" style="background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.2); border-radius: 8px; color: #10B981; padding: 12px 16px; font-size: 13px; font-weight: 600;">✓ Ruangan tersedia pada jadwal ini. Silakan lanjutkan pengajuan.</div>';
                } else {
                    notice.innerHTML = '<div class="alert mb-2" style="background: rgba(225,29,72,.1); border: 1px solid rgba(225,29,72,.2); border-radius: 8px; color: #E11D48; padding: 12px 16px; font-size: 13px; font-weight: 600;">⚠ Ruangan bentrok dengan booking lain pada jadwal ini. Silakan pilih jam atau ruangan lain.</div>';
                }
            })
            .catch(function () {});
    }

    [roomSelect, tanggalInput, jamMulaiInput, jamSelesaiInput].forEach(function (el) {
        el.addEventListener('change', checkAvailability);
    });
});
</script>
@endpush
