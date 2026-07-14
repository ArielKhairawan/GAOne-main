@extends('layouts.app')

@section('title', $type === 'masuk' ? 'Barang Masuk' : 'Barang Keluar')
@section('page-title', $type === 'masuk' ? 'Catat Barang Masuk' : 'Catat Barang Keluar')
@section('page-subtitle', $type === 'masuk' ? 'Lengkapi formulir di bawah untuk menambahkan stok barang ATK baru.' : 'Lengkapi formulir di bawah untuk mencatat pengurangan stok barang ATK.')

@section('content')

<!-- Header Form -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route($type === 'masuk' ? 'atk.stock-in.index' : 'atk.stock-out.index') }}" style="background: var(--surface-3); color: var(--text); border: 1px solid var(--border); font-weight: 600; padding: 8px 16px; border-radius: 8px; display: flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
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

<!-- Form Transaksi Stok -->
<form method="post" action="{{ route($type === 'masuk' ? 'atk.stock-in.store' : 'atk.stock-out.store') }}">
    @csrf

    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">

        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">
                {{ $type === 'masuk' ? 'Detail Transaksi Barang Masuk' : 'Detail Transaksi Barang Keluar' }}
            </div>
        </div>

        <div style="padding: 32px 24px;">
            <div class="row g-4">

                <!-- Pilih Barang -->
                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Barang <span style="color: #E11D48;">*</span></label>
                    <select class="form-select" name="atk_item_id" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                        <option value="">— Pilih Barang —</option>
                        @foreach($items as $i)
                            <option value="{{ $i->id }}" @selected((int) old('atk_item_id') === $i->id)>
                                {{ $i->name }} (Stok saat ini: {{ $i->stock }} {{ $i->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jumlah / Quantity -->
                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Jumlah <span style="color: #E11D48;">*</span></label>
                    <input type="number" min="1" class="form-control" name="quantity" value="{{ old('quantity') }}" placeholder="Masukkan jumlah kuantitas..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>

                <!-- Catatan -->
                <div class="col-12">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Catatan / Keterangan</label>
                    <textarea class="form-control" name="notes" rows="3" placeholder="Tuliskan alasan atau keterangan transaksi terkait..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 12px 14px; border-radius: 8px;">{{ old('notes') }}</textarea>
                </div>

            </div>
        </div>
    </div>

    <!-- Tombol Simpan & Batal -->
    <div class="d-flex gap-3 mt-2 mb-5">
        <button class="btn" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 32px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);">
            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/></svg>
            Simpan Catatan
        </button>
        <a class="btn" href="{{ route($type === 'masuk' ? 'atk.stock-in.index' : 'atk.stock-out.index') }}" style="background: transparent; color: #64748B; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 24px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Batal</a>
    </div>
</form>

@endsection
