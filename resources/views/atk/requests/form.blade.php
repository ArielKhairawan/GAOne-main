@extends('layouts.app')

@section('title', 'Buat Permintaan ATK')
@section('page-title', 'Buat Permintaan ATK')
@section('page-subtitle', 'Permintaan akan diproses oleh GA Staff, lalu disetujui oleh Manager.')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('atk.requests.index') }}" style="background: var(--surface-3); color: var(--text); border: 1px solid var(--border); font-weight: 600; padding: 8px 16px; border-radius: 8px; display: flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
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

<form method="post" action="{{ route('atk.requests.store') }}">
    @csrf

    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Informasi Pengaju</div>
        </div>
        <div style="padding: 28px 24px;">
            <div class="row g-4">
                <div class="col-md-6">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Departemen <span style="color: #E11D48;">*</span></label>
                    <input type="text" class="form-control" name="department" value="{{ old('department', auth()->user()->department) }}" placeholder="Masukkan nama departemen..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>
                </div>
                <div class="col-12">
                    <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; display: block;">Catatan / Keperluan</label>
                    <textarea class="form-control" name="notes" rows="2" placeholder="Tuliskan keterangan tambahan atau tujuan permintaan barang..." style="background: #ffffff; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 14px; padding: 12px 14px; border-radius: 8px;">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 16px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Daftar Kebutuhan ATK</div>
            <button type="button" id="addRow" class="btn btn-sm hover-scale-btn" style="background: rgba(59,130,246,.08); color: #3B82F6; border: none; font-weight: 600; padding: 6px 14px; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13px; transition: all 0.15s;">
                + Tambah Baris
            </button>
        </div>
        <div style="padding: 24px;">
            <div id="itemRows" class="d-flex flex-column gap-3"></div>
        </div>
    </div>

    <div class="d-flex gap-3 mt-2 mb-5">
        <button class="btn" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 32px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);">
            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            Ajukan Permintaan
        </button>
        <a class="btn" href="{{ route('atk.requests.index') }}" style="background: transparent; color: #64748B; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 24px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Batal</a>
    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var items = @json($items->map(fn($i) => ['id' => $i->id, 'name' => $i->name.' (stok: '.$i->stock.' '.$i->satuan.')']));
    var container = document.getElementById('itemRows');
    var rowIndex = 0;

    function buildOptions(selected) {
        return items.map(function (it) {
            var sel = String(it.id) === String(selected) ? 'selected' : '';
            return '<option value="' + it.id + '" ' + sel + '>' + it.name + '</option>';
        }).join('');
    }

    function addRow() {
        var idx = rowIndex++;
        var row = document.createElement('div');
        row.className = 'row g-2 align-items-center dynamic-item-row';
        row.innerHTML =
            '<div class="col-md-7">' +
                '<select class="form-select" name="items[' + idx + '][atk_item_id]" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: \'Poppins\', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>' +
                    '<option value="">— Pilih Barang —</option>' + buildOptions(null) +
                '</select>' +
            '</div>' +
            '<div class="col-md-3">' +
                '<input type="number" min="1" class="form-control" name="items[' + idx + '][quantity]" placeholder="Jumlah" style="background: #ffffff; border: 1px solid #E2E8F0; font-family: \'Poppins\', sans-serif; font-size: 14px; padding: 10px 14px; border-radius: 8px;" required>' +
            '</div>' +
            '<div class="col-md-2">' +
                '<button type="button" class="btn btn-sm removeRow" style="width:100%; background: rgba(225,29,72,.06); color: #E11D48; border: none; font-weight: 600; padding: 10px 14px; border-radius: 8px; font-family: \'Poppins\', sans-serif; font-size: 13.5px; transition: all 0.2s;">Hapus</button>' +
            '</div>';
        container.appendChild(row);

        row.querySelector('.removeRow').addEventListener('click', function () {
            row.remove();
        });
    }

    document.getElementById('addRow').addEventListener('click', addRow);
    addRow(); // Membuat baris default pertama
});
</script>
@endpush
