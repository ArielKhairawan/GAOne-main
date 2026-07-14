@extends('layouts.app')

@section('title', 'Verifikasi QR SIK')
@section('page-title', 'Verifikasi QR Surat Izin Keluar')
@section('page-subtitle', 'Halaman ini adalah tujuan QR Code — proses scan tetap tercatat sebagai riwayat scan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Security</span>
        <h1 class="section-title">Verifikasi QR</h1>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('sik.security.scan') }}">Ke Halaman Scan</a>
</div>

<div class="card">
    <div class="card-body p-4">
        @if(! $sik)
            <div class="sik-scan-result error">
                <strong>{{ config('sik.validation_messages.not_found') }}</strong>
            </div>
        @else
            <div class="row g-3 mb-4">
                <div class="col-md-4"><label class="form-label">Nama</label><div class="fw-medium">{{ $sik->user->name }}</div></div>
                <div class="col-md-4"><label class="form-label">Nomor SIK</label><div class="fw-medium">{{ $sik->nomor_sik ?: '—' }}</div></div>
                <div class="col-md-4"><label class="form-label">Status</label><div><span class="status-badge sik-{{ $sik->status }}">{{ $sik->status_label }}</span></div></div>
            </div>

            @if(in_array($sik->status, ['approved', 'sedang_keluar']))
                <form method="post" action="{{ route('sik.security.scan.process') }}" id="verify-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <button type="submit" class="btn btn-primary" id="verify-btn">
                        {{ $sik->status === 'approved' ? 'Proses Scan Keluar' : 'Proses Scan Kembali' }}
                    </button>
                </form>
            @else
                <div class="sik-scan-result error">
                    <strong>
                        @if($sik->status === 'completed') {{ config('sik.validation_messages.completed') }}
                        @elseif($sik->status === 'pending_approval') {{ config('sik.validation_messages.pending_approval') }}
                        @elseif($sik->status === 'rejected') {{ config('sik.validation_messages.rejected') }}
                        @elseif($sik->status === 'cancelled') {{ config('sik.validation_messages.cancelled') }}
                        @endif
                    </strong>
                </div>
            @endif

            <div id="verify-result" class="mt-3"></div>
        @endif
    </div>
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
        document.getElementById('verify-result').innerHTML =
            `<div class="sik-scan-result ${data.success ? 'success' : 'error'}"><strong>${data.message}</strong></div>`;
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
