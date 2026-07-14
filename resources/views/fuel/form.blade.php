@extends('layouts.app')

@section('title', ($fuelLog->exists ? 'Edit' : 'Tambah') . ' Data BBM')
@section('page-title', ($fuelLog->exists ? 'Edit' : 'Tambah') . ' Data Pengisian BBM')
@section('page-subtitle', 'Kilometer akhir dan jumlah liter digunakan untuk menghitung konsumsi otomatis.')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('fuel.index') }}" style="background: var(--surface-3); color: var(--text); border: 1px solid var(--border); font-weight: 600; padding: 8px 16px; border-radius: 8px; display: flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
        Kembali ke Daftar
    </a>
</div>

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

<form method="post" action="{{ $fuelLog->exists ? route('fuel.update', $fuelLog) : route('fuel.store') }}">
    @csrf
    @if($fuelLog->exists) @method('PUT') @endif

    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">

        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Informasi Log Pengisian BBM</div>
        </div>

        <div style="padding: 32px 24px;">
            <div class="row g-4">

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Kendaraan <span style="color: #E11D48;">*</span></label>
                    <select class="form-select" name="vehicle_id" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                        <option value="">— Pilih Kendaraan —</option>
                        @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" @selected((int) old('vehicle_id', $fuelLog->vehicle_id) === $v->id)>{{ $v->plat_nomor }} — {{ $v->jenis_kendaraan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Tanggal Pengisian <span style="color: #E11D48;">*</span></label>
                    <input type="date" class="form-control" name="tanggal_pengisian" value="{{ old('tanggal_pengisian', optional($fuelLog->tanggal_pengisian)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Driver (Nama Manual)</label>
                    <input type="text" class="form-control" name="driver" value="{{ old('driver', $fuelLog->driver) }}" placeholder="Ketik nama driver luar/manual..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Driver (Akun Internal)</label>
                    <select class="form-select" name="driver_id" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                        <option value="">— Tidak Terhubung Akun —</option>
                        @foreach($driverOptions as $d)
                            <option value="{{ $d->id }}" @selected((int) old('driver_id', $fuelLog->driver_id) === $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Jenis Bahan Bakar <span style="color: #E11D48;">*</span></label>
                    <input type="text" class="form-control" name="jenis_bahan_bakar" list="fuelTypeOptions" value="{{ old('jenis_bahan_bakar', $fuelLog->jenis_bahan_bakar) }}" placeholder="Cth: Pertalite, Biosolar..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                    <datalist id="fuelTypeOptions">
                        @foreach($fuelTypes as $type)
                            <option value="{{ $type }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Harga per Liter (Rp) <span style="color: #E11D48;">*</span></label>
                    <input type="number" step="0.01" min="0" class="form-control calc-input" id="hargaPerLiter" name="harga_per_liter" value="{{ old('harga_per_liter', $fuelLog->harga_per_liter) }}" placeholder="Cth: 10000" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Jumlah Liter <span style="color: #E11D48;">*</span></label>
                    <input type="number" step="0.01" min="0.01" class="form-control calc-input" id="jumlahLiter" name="jumlah_liter" value="{{ old('jumlah_liter', $fuelLog->jumlah_liter) }}" placeholder="Cth: 25.5" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Kilometer Awal <span style="color: #E11D48;">*</span></label>
                    <input type="number" min="0" class="form-control calc-input" id="kmAwal" name="kilometer_awal" value="{{ old('kilometer_awal', $fuelLog->kilometer_awal) }}" placeholder="Masukkan KM awal..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Kilometer Akhir <span style="color: #E11D48;">*</span></label>
                    <input type="number" min="0" class="form-control calc-input" id="kmAkhir" name="kilometer_akhir" value="{{ old('kilometer_akhir', $fuelLog->kilometer_akhir) }}" placeholder="Masukkan KM akhir..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <div class="col-md-12">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Kalkulasi Estimasi Efisiensi</label>
                    <div style="background: #F8FAFC; border: 1px dashed #CBD5E1; border-radius: 12px; padding: 16px 20px; display: flex; flex-wrap: wrap; gap: 32px;">
                        <div>
                            <span style="font-size: 12px; color: #64748B; font-weight: 500; display: block; margin-bottom: 2px;">TOTAL BIAYA</span>
                            <span id="previewTotal" style="font-size: 16px; font-weight: 700; color: #0F172A;">Rp 0</span>
                        </div>
                        <div style="border-left: 1px solid #E2E8F0; padding-left: 32px;">
                            <span style="font-size: 12px; color: #64748B; font-weight: 500; display: block; margin-bottom: 2px;">RATA-RATA KONSUMSI BBM</span>
                            <span id="previewKonsumsi" style="font-size: 16px; font-weight: 700; color: #10B981;">—</span>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Keterangan Tambahan</label>
                    <textarea class="form-control" name="keterangan" rows="3" placeholder="Tulis catatan rute perjalanan atau info tambahan nota pengisian..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 12px 14px; border-radius: 8px;">{{ old('keterangan', $fuelLog->keterangan) }}</textarea>
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex gap-3 mt-2 mb-5">
        <button class="btn" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 32px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);">
            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
            Simpan Log
        </button>
        <a class="btn" href="{{ route('fuel.index') }}" style="background: transparent; color: #64748B; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 24px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Batal</a>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var hargaPerLiter = document.getElementById('hargaPerLiter');
    var jumlahLiter = document.getElementById('jumlahLiter');
    var kmAwal = document.getElementById('kmAwal');
    var kmAkhir = document.getElementById('kmAkhir');
    var previewTotal = document.getElementById('previewTotal');
    var previewKonsumsi = document.getElementById('previewKonsumsi');

    function formatRupiah(n) {
        return 'Rp ' + Number(n || 0).toLocaleString('id-ID', { maximumFractionDigits: 0 });
    }

    function recalc() {
        var harga = parseFloat(hargaPerLiter.value) || 0;
        var liter = parseFloat(jumlahLiter.value) || 0;
        var awal = parseInt(kmAwal.value, 10) || 0;
        var akhir = parseInt(kmAkhir.value, 10) || 0;
        var jarak = Math.max(0, akhir - awal);

        previewTotal.textContent = formatRupiah(harga * liter);
        previewKonsumsi.textContent = liter > 0
            ? (jarak / liter).toFixed(2) + ' km/liter (Jarak Tempuh: ' + jarak + ' km)'
            : '—';
    }

    [hargaPerLiter, jumlahLiter, kmAwal, kmAkhir].forEach(function (el) {
        el.addEventListener('input', recalc);
    });

    recalc();
});
</script>
@endpush
