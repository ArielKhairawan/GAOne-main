@extends('layouts.app')

@section('title', 'Buat Permintaan Konsumsi')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('consumption.index') }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Kembali
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-4" style="border-radius: 12px; font-family: 'Poppins', sans-serif; font-size: 13.5px;">
    <strong>Periksa kembali isian Anda:</strong>
    <ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form method="post" action="{{ route('consumption.store') }}">
    @csrf
    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 28px; font-family: 'Poppins', sans-serif;">
        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Tanggal <span style="color:#EF4444">*</span></label>
                <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Departemen</label>
                <input type="text" class="form-control" name="departemen" value="{{ old('departemen', auth()->user()->department) }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
            </div>
            <div class="col-md-4">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Jumlah Peserta <span style="color:#EF4444">*</span></label>
                <input type="number" min="1" class="form-control" name="jumlah_peserta" value="{{ old('jumlah_peserta') }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
            </div>
            <div class="col-12">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Nama Acara / Kegiatan <span style="color:#EF4444">*</span></label>
                <input type="text" class="form-control" name="nama_acara" value="{{ old('nama_acara') }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;" placeholder="Masukkan nama agenda atau acara resmi">
            </div>
            <div class="col-12">
                <label class="form-label mb-2" style="font-size: 12px; font-weight: 600; color: #475569;">Jenis Konsumsi <span style="color:#EF4444">*</span></label>
                <div class="row g-2">
                    @foreach(config('monitoring.consumption_types') as $type)
                    <div class="col-md-3 col-6">
                        <div class="form-check d-flex align-items-center gap-2 mb-1">
                            <input type="checkbox" class="form-check-input" name="jenis_konsumsi[]" id="jk-{{ $loop->index }}" value="{{ $type }}" @checked(in_array($type, old('jenis_konsumsi', []))) style="width: 16px; height: 16px; border: 1px solid #CBD5E1; cursor: pointer;">
                            <label class="form-check-label" for="jk-{{ $loop->index }}" style="font-size: 13px; color: #334155; cursor: pointer; font-weight: 500; user-select: none;">{{ $type }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-12">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Detail Konsumsi</label>
                <textarea class="form-control" name="detail_konsumsi" rows="4" placeholder="Contoh: Nasi Kotak Ayam Bakar (20 porsi), Air Mineral Gelas (1 dus)" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; padding: 12px; resize: vertical;">{{ old('detail_konsumsi') }}</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2" style="font-family: 'Poppins', sans-serif;">
        <button type="submit" class="btn btn-sm" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 12px 28px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            Ajukan Permintaan
        </button>
        <a class="btn btn-sm" href="{{ route('consumption.index') }}" style="background: #F1F5F9; color: #475569; border: none; font-weight: 600; padding: 12px 24px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;">
            Batal
        </a>
    </div>
</form>

@endsection
