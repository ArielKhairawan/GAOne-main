@extends('layouts.app')

@section('title', 'Scan QR SIK')

@section('content')

<!-- Header Form (Tombol Kembali di Sebelah Kanan) -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('sik.security.dashboard') }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Dashboard
    </a>
</div>

<!-- Kontainer Pemindai QR -->
<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 32px; font-family: 'Poppins', sans-serif; text-center">
    <div class="mx-auto" style="max-width: 480px; width: 100%;">
        <!-- Wadah Kamera QR -->
        <div id="sik-scanner-reader" style="border-radius: 12px; overflow: hidden; border: 1px solid #E2E8F0; background: #000;"></div>

        <!-- Status Pemindai -->
        <div id="sik-scanner-status" class="small mt-3" style="color: #64748B; font-weight: 500; font-size: 13px;">Menyiapkan kamera…</div>

        <!-- Hasil Scan yang Dinamis -->
        <div id="sik-scan-result" class="mt-4"></div>

        <!-- Tombol Kontrol Jeda Kamera -->
        <div class="mt-4">
            <button id="sik-scan-toggle" class="btn btn-sm" style="background: #F1F5F9; color: #475569; border: none; font-weight: 600; padding: 10px 20px; border-radius: 8px; width: 100%; transition: all 0.2s;">
                Jeda Kamera
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusEl = document.getElementById('sik-scanner-status');
    const resultEl = document.getElementById('sik-scan-result');
    const toggleBtn = document.getElementById('sik-scan-toggle');
    const csrfToken = '{{ csrf_token() }}';
    const scanEndpoint = '{{ route('sik.security.scan.process') }}';

    let isProcessing = false;
    let isPaused = false;
    let lastToken = null;
    let lastAt = 0;

    const html5QrCode = new Html5Qrcode('sik-scanner-reader');

    function extractToken(decodedText) {
        try {
            const url = new URL(decodedText);
            const parts = url.pathname.split('/').filter(Boolean);
            return parts[parts.length - 1];
        } catch (e) {
            return decodedText; // bukan URL, anggap sebagai token mentah
        }
    }

    function showResult(success, message, sik) {
        const bg = success ? '#DEF7EC' : '#FDE8E8';
        const color = success ? '#03543F' : '#9B1C1C';
        const borderColor = success ? '#BCF0DA' : '#FBD5D5';

        let innerContent = `<strong style="font-size: 14px; display: block; margin-bottom: 4px;">${message}</strong>`;
        if (sik) {
            innerContent += `
                <div style="font-size: 12.5px; margin-top: 10px; text-align: left; background: rgba(255, 255, 255, 0.6); padding: 12px; border-radius: 8px; line-height: 1.5;">
                    ${sik.nomor_sik ? '<b>Nomor SIK:</b> ' + sik.nomor_sik + '<br>' : ''}
                    <b>Nama:</b> ${sik.nama}<br>
                    <b>Departemen:</b> ${sik.department ?? '-'}<br>
                    <b>Status:</b> <span class="badge" style="background: #3B82F6; color: white; font-size: 10px; padding: 2px 6px; border-radius: 4px;">${sik.status_label}</span>
                </div>`;
        }

        resultEl.innerHTML = `
            <div style="background: ${bg}; color: ${color}; border: 1px solid ${borderColor}; border-radius: 12px; padding: 16px; text-align: center;">
                ${innerContent}
            </div>`;
    }

    async function onScanSuccess(decodedText) {
        const now = Date.now();
        if (isProcessing) return;
        if (decodedText === lastToken && (now - lastAt) < 4000) return; // cegah double-submit QR yang sama

        isProcessing = true;
        lastToken = decodedText;
        lastAt = now;
        statusEl.textContent = 'Memproses scan...';

        const token = extractToken(decodedText);

        try {
            const res = await fetch(scanEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ token }),
            });
            const data = await res.json();
            showResult(data.success, data.message, data.sik);
        } catch (e) {
            showResult(false, 'Terjadi kesalahan koneksi ke server. Coba lagi.');
        } finally {
            statusEl.textContent = 'Arahkan kamera ke QR Code berikutnya…';
            isProcessing = false;
        }
    }

    html5QrCode.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        onScanSuccess
    ).then(() => {
        statusEl.textContent = 'Arahkan kamera ke QR Code SIK…';
    }).catch(() => {
        statusEl.textContent = 'Akses kamera ditolak. Pastikan izin kamera browser telah diizinkan.';
    });

    toggleBtn.addEventListener('click', function () {
        if (!isPaused) {
            html5QrCode.pause(true);
            isPaused = true;
            toggleBtn.textContent = 'Lanjutkan Kamera';
            statusEl.textContent = 'Kamera dijeda.';
        } else {
            html5QrCode.resume();
            isPaused = false;
            toggleBtn.textContent = 'Jeda Kamera';
            statusEl.textContent = 'Arahkan kamera ke QR Code SIK…';
        }
    });
});
</script>
@endpush
