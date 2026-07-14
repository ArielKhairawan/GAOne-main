@extends('layouts.app')

@section('title', 'Scan QR SIK')
@section('page-title', 'Scan QR Surat Izin Keluar')
@section('page-subtitle', 'Arahkan kamera ke QR Code pada Surat Izin Keluar karyawan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Security</span>
        <h1 class="section-title">Scan QR</h1>
        <p class="section-subtitle">Status keluar/kembali akan diproses otomatis sesuai status SIK saat ini.</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('sik.security.dashboard') }}">Kembali</a>
</div>

<div class="card">
    <div class="card-body p-4 text-center">
        <div id="sik-scanner-reader"></div>
        <div id="sik-scanner-status" class="small text-muted mt-3">Menyiapkan kamera…</div>
        <div id="sik-scan-result"></div>

        <div class="mt-4">
            <button id="sik-scan-toggle" class="btn btn-outline-secondary">Jeda Kamera</button>
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
        resultEl.innerHTML = `
            <div class="sik-scan-result ${success ? 'success' : 'error'}">
                <strong>${message}</strong>
                ${sik ? `<div class="mt-2 small">
                    ${sik.nomor_sik ? 'Nomor SIK: ' + sik.nomor_sik + '<br>' : ''}
                    Nama: ${sik.nama}<br>
                    Departemen: ${sik.department ?? '-'}<br>
                    Status: ${sik.status_label}
                </div>` : ''}
            </div>`;
    }

    async function onScanSuccess(decodedText) {
        const now = Date.now();
        if (isProcessing) return;
        if (decodedText === lastToken && (now - lastAt) < 4000) return; // cegah double-submit QR yang sama

        isProcessing = true;
        lastToken = decodedText;
        lastAt = now;
        statusEl.textContent = 'Memproses…';

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
            showResult(false, 'Terjadi kesalahan koneksi. Coba lagi.');
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
        statusEl.textContent = 'Arahkan kamera ke QR Code…';
    }).catch(() => {
        statusEl.textContent = 'Kamera tidak dapat diakses. Pastikan izin kamera browser diaktifkan.';
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
            statusEl.textContent = 'Arahkan kamera ke QR Code…';
        }
    });
});
</script>
@endpush
