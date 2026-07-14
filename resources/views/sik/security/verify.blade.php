@extends('layouts.app')

@section('title', 'Verifikasi QR SIK')

@section('content')

<!-- Header Form (Tombol Navigasi di Sebelah Kanan) -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('sik.security.scan') }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
        Ke Halaman Scan
    </a>
</div>

<!-- Kontainer Utama Verifikasi -->
<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 28px; font-family: 'Poppins', sans-serif;">

    @if(! $sik)
        <div style="background: #FDE8E8; color: #9B1C1C; border: 1px solid #FBD5D5; border-radius: 12px; padding: 16px; text-align: center;">
            <strong style="font-size: 14px;">{{ config('sik.validation_messages.not_found') }}</strong>
        </div>
    @else
        <div style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 24px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px;">
            Verifikasi SIK Manual
        </div>

        <!-- Detail Pemohon -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <label style="font-size: 12px; font-weight: 600; color: #64748B;">Nama Pemohon</label>
                <div style="font-size: 14px; font-weight: 600; color: #1E293B; margin-top: 4px;">{{ $sik->user->name }}</div>
            </div>
            <div class="col-md-4">
                <label style="font-size: 12px; font-weight: 600; color: #64748B;">Nomor SIK</label>
                <div style="font-size: 14px; font-weight: 600; color: #1E293B; margin-top: 4px;">{{ $sik->nomor_sik ?: '—' }}</div>
            </div>
            <div class="col-md-4">
                <label style="font-size: 12px; font-weight: 600; color: #64748B;">Status Saat Ini</label>
                <div style="margin-top: 4px;">
                    <span class="badge" style="background: #E0F2FE; color: #0369A1; font-weight: 600; font-size: 11px; padding: 6px 12px; border-radius: 6px;">
                        {{ $sik->status_label }}
                    </span>
                </div>
            </div>
        </div>

        @if(in_array($sik->status, ['approved', 'sedang_keluar']))
            <!-- Form Aksi Integrasi Manual -->
            <form method="post" action="{{ route('sik.security.scan.process') }}" id="verify-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <button type="submit" class="btn btn-sm" id="verify-btn" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 12px 28px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15); width: 100%; justify-content: center;">
                    {{ $sik->status === 'approved' ? 'Proses Scan Keluar' : 'Proses Scan Kembali' }}
                </button>
            </form>
        @else
            <div style="background: #F3F4F6; color: #4B5563; border: 1px solid #E5E7EB; border-radius: 12px; padding: 16px; text-align: center;">
                <strong style="font-size: 14.5px;">
                    @if($sik->status === 'completed') {{ config('sik.validation_messages.completed') }}
                    @elseif($sik->status === 'pending_approval') {{ config('sik.validation_messages.pending_approval') }}
                    @elseif($sik->status === 'rejected') {{ config('sik.validation_messages.rejected') }}
                    @elseif($sik->status === 'cancelled') {{ config('sik.validation_messages.cancelled') }}
                    @endif
                </strong>
            </div>
        @endif

        <div id="verify-result" class="mt-4"></div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.getElementById('verify-form')?.addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('verify-btn');
    btn.disabled = true;

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ token: form.querySelector('[name=token]').value }),
        });
        const data = await res.json();

        const bg = data.success ? '#DEF7EC' : '#FDE8E8';
        const color = data.success ? '#03543F' : '#9B1C1C';
        const borderColor = data.success ? '#BCF0DA' : '#FBD5D5';

        document.getElementById('verify-result').innerHTML = `
            <div style="background: ${bg}; color: ${color}; border: 1px solid ${borderColor}; border-radius: 12px; padding: 16px; text-align: center;">
                <strong style="font-size: 14px;">${data.message}</strong>
            </div>`;

        if (data.success) {
            setTimeout(() => window.location.reload(), 1500);
        } else {
            btn.disabled = false;
        }
    } catch (e) {
        btn.disabled = false;
    }
});
</script>
@endpush
