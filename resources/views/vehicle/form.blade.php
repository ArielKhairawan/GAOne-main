@extends('layouts.app')

@section('title', ($vehicle->exists ? 'Edit' : 'Tambah') . ' Kendaraan')
@section('page-title', ($vehicle->exists ? 'Edit' : 'Tambah') . ' Data Kendaraan')
@section('page-subtitle', $vehicle->exists ? 'Perbarui informasi kendaraan ' . $vehicle->plat_nomor : 'Lengkapi formulir di bawah untuk menambahkan kendaraan baru.')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('vehicle.index') }}" style="background: var(--surface-3); color: var(--text); border: 1px solid var(--border); font-weight: 600; padding: 8px 16px; border-radius: 8px; display: flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
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

<form method="post" action="{{ $vehicle->exists ? route('vehicle.update', $vehicle) : route('vehicle.store') }}">
    @csrf
    @if($vehicle->exists) @method('PUT') @endif

    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">

        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Informasi Detail Kendaraan</div>
        </div>

        <div style="padding: 32px 24px;">
            <div class="row g-4">

                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Plat Nomor <span style="color: #E11D48;">*</span></label>
                    <input type="text" class="form-control" name="plat_nomor" value="{{ old('plat_nomor', $vehicle->plat_nomor) }}" placeholder="Contoh: B 1234 ABC" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Jenis Kendaraan <span style="color: #E11D48;">*</span></label>
                    <input type="text" class="form-control" name="jenis_kendaraan" list="jenisKendaraanOptions" value="{{ old('jenis_kendaraan', $vehicle->jenis_kendaraan) }}" placeholder="Contoh: Mobil Operasional" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                    <datalist id="jenisKendaraanOptions">
                        @foreach(config('monitoring.vehicle_types') as $type)
                            <option value="{{ $type }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Merk Kendaraan</label>
                    <input type="text" class="form-control" name="merk" value="{{ old('merk', $vehicle->merk) }}" placeholder="Contoh: Toyota Avanza" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Tahun Pembuatan</label>
                    <input type="number" class="form-control" name="tahun" min="1980" max="{{ now()->year + 1 }}" value="{{ old('tahun', $vehicle->tahun) }}" placeholder="Contoh: 2022" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Status <span style="color: #E11D48;">*</span></label>
                    <select class="form-select" name="status" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                        @foreach(config('monitoring.vehicle_statuses') as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $vehicle->status ?? 'aktif') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Driver (Nama Manual)</label>
                    <input type="text" class="form-control" name="driver" value="{{ old('driver', $vehicle->driver) }}" placeholder="Ketik nama driver..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Driver (Hubungkan ke Akun)</label>
                    <select class="form-select" name="driver_id" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px; margin-bottom: 6px;">
                        <option value="">— Tidak Terhubung Akun —</option>
                        @foreach($driverOptions as $d)
                            <option value="{{ $d->id }}" @selected((int) old('driver_id', $vehicle->driver_id) === $d->id)>{{ $d->name }}</option>
                        @endforeach
                    </select>
                    <div style="font-size: 11.5px; color: #64748B;">Hubungkan ke akun agar muncul di dashboard Driver terkait.</div>
                </div>

                <div class="col-12">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Keterangan Tambahan</label>
                    <textarea class="form-control" name="keterangan" rows="3" placeholder="Tuliskan catatan opsional terkait kendaraan ini..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 12px 14px; border-radius: 8px;">{{ old('keterangan', $vehicle->keterangan) }}</textarea>
                </div>

            </div>
        </div>
    </div>

    <div class="d-flex gap-3 mt-2 mb-5">
        <button class="btn" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 32px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);">
            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
            Simpan Data Kendaraan
        </button>
        <a class="btn" href="{{ route('vehicle.index') }}" style="background: transparent; color: #64748B; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 24px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Batal</a>
    </div>
</form>

@endsection
