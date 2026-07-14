@extends('layouts.app')

@section('title', ($item->exists ? 'Edit' : 'Tambah') . ' Barang ATK')
@section('page-title', ($item->exists ? 'Edit' : 'Tambah') . ' Barang ATK')
@section('page-subtitle', $item->exists ? 'Perbarui informasi barang ' . $item->name : 'Lengkapi formulir di bawah untuk menambahkan barang ATK baru.')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('atk.items.index') }}" style="background: var(--surface-3); color: var(--text); border: 1px solid var(--border); font-weight: 600; padding: 8px 16px; border-radius: 8px; display: flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
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

<form method="post" action="{{ $item->exists ? route('atk.items.update', $item) : route('atk.items.store') }}">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">

        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Informasi Detail Barang</div>
        </div>

        <div style="padding: 32px 24px;">
            <div class="row g-4">

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Kode Barang <span style="color: #E11D48;">*</span></label>
                    <input type="text" class="form-control" name="code" value="{{ old('code', $item->code) }}" placeholder="Contoh: ATK-001" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                <div class="col-md-8">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Nama Barang <span style="color: #E11D48;">*</span></label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $item->name) }}" placeholder="Contoh: Kertas A4 80gr" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Kategori <span style="color: #E11D48;">*</span></label>
                    <select class="form-select" name="atk_category_id" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((int) old('atk_category_id', $item->atk_category_id) === $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Satuan <span style="color: #E11D48;">*</span></label>
                    <input type="text" class="form-control" name="satuan" list="satuanOptions" value="{{ old('satuan', $item->satuan ?? 'pcs') }}" placeholder="Pilih atau ketik..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                    <datalist id="satuanOptions">
                        @foreach(config('monitoring.atk_units') as $unit)
                            <option value="{{ $unit }}">
                        @endforeach
                    </datalist>
                </div>

                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Lokasi Penyimpanan</label>
                    <input type="text" class="form-control" name="lokasi_penyimpanan" value="{{ old('lokasi_penyimpanan', $item->lokasi_penyimpanan) }}" placeholder="Contoh: Gudang A - Rak 2" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                @if(! $item->exists)
                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Stok Awal <span style="color: #E11D48;">*</span></label>
                    <input type="number" min="0" class="form-control" name="stock" value="{{ old('stock', 0) }}" placeholder="0" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>
                @endif

                <div class="{{ $item->exists ? 'col-md-12' : 'col-md-6' }}">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Stok Minimum <span style="color: #E11D48;">*</span></label>
                    <input type="number" min="0" class="form-control" name="minimum_stock" value="{{ old('minimum_stock', $item->minimum_stock ?? 0) }}" placeholder="0" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;">
                </div>

                @if($item->exists)
                <div class="col-12">
                    <div style="background: #F8FAFC; border: 1px solid var(--border); border-radius: 8px; padding: 14px 18px; font-size: 12.5px; color: #64748B; font-family: 'Poppins', sans-serif; line-height: 1.5;">
                        Stok saat ini: <strong style="color: #0F172A;">{{ $item->stock }} {{ $item->satuan }}</strong>.
                        <br>Gunakan menu <a href="{{ route('atk.masuk.index') }}" style="color: #3B82F6; font-weight: 600; text-decoration: none;">Barang Masuk</a> atau <a href="{{ route('atk.keluar.index') }}" style="color: #3B82F6; font-weight: 600; text-decoration: none;">Barang Keluar</a> untuk mengubah jumlah stok.
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <div class="d-flex gap-3 mt-2 mb-5">
        <button class="btn" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 32px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);">
            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
            Simpan Data Barang
        </button>
        <a class="btn" href="{{ route('atk.items.index') }}" style="background: transparent; color: #64748B; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 24px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Batal</a>
    </div>
</form>

@endsection
