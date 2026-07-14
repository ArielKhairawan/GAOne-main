@extends('layouts.app')

@section('title', 'Beri Rating')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('csat.index') }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Kembali
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-4" style="border-radius: 12px; font-family: 'Poppins', sans-serif; font-size: 13.5px;">
    {{ $errors->first() }}
</div>
@endif

<form method="post" action="{{ route('csat.store', $survey) }}">
    @csrf
    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 28px; font-family: 'Poppins', sans-serif;">

        <div class="mb-4 p-3 style-badge" style="background: #F8FAFC; border-radius: 10px; border: 1px solid #E2E8F0;">
            <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Layanan / Modul Dinilai</div>
            <div style="font-size: 16px; font-weight: 700; color: #0F172A; margin-top: 2px;">{{ $moduleLabels[$survey->service_type] ?? $survey->service_type }}</div>
        </div>

        <div class="mb-4">
            <label class="form-label mb-3" style="font-size: 13px; font-weight: 600; color: #475569;">Rating Kepuasan Anda <span style="color:#EF4444">*</span></label>
            <div class="d-flex flex-column gap-2">
                @foreach($ratingLabels as $value => $label)
                <div class="form-check d-flex align-items-center gap-2 p-2" style="border-radius: 8px; border: 1px solid #F1F5F9; transition: background 0.2s; cursor: pointer;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <input type="radio" class="form-check-input" name="rating" id="rating-{{ $value }}" value="{{ $value }}" @checked(old('rating') == $value) style="width: 18px; height: 18px; border: 1px solid #CBD5E1; cursor: pointer; margin-left: 4px;">
                    <label class="form-check-label" for="rating-{{ $value }}" style="font-size: 13.5px; color: #1E293B; cursor: pointer; font-weight: 500; user-select: none; width: 100%; padding-left: 4px;">
                        <span style="font-weight: 700; color: #3B82F6; margin-right: 4px;">{{ $value }}</span> — {{ $label }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mb-2">
            <label class="form-label mb-2" style="font-size: 13px; font-weight: 600; color: #475569;">Komentar / Masukan <span style="color: #94A3B8; font-weight: 400;">(Opsional)</span></label>
            <textarea class="form-control" name="comments" rows="4" placeholder="Berikan umpan balik atau saran kritik untuk membantu kami meningkatkan kualitas layanan..." style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 8px; padding: 14px; resize: vertical;">{{ old('comments') }}</textarea>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2" style="font-family: 'Poppins', sans-serif;">
        <button type="submit" class="btn btn-sm" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 12px 28px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            Kirim Penilaian
        </button>
        <a class="btn btn-sm" href="{{ route('csat.index') }}" style="background: #F1F5F9; color: #475569; border: none; font-weight: 600; padding: 12px 24px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;">
            Batal
        </a>
    </div>
</form>

@endsection
