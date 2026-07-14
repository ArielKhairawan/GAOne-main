@extends('layouts.app')

@section('title', 'Dashboard Scan')
@section('page-title', 'Dashboard Scan Security')
@section('page-subtitle', 'Ringkasan aktivitas scan QR Surat Izin Keluar hari ini')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <span class="section-eyebrow">Security</span>
        <h1 class="section-title">Dashboard Scan</h1>
        <p class="section-subtitle">{{ now()->translatedFormat('l, d F Y') }}</p>
    </div>
    <a class="btn btn-primary" href="{{ route('sik.security.scan') }}">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h6v2H6v4H4V4zm10 0h6v6h-2V6h-4V4zM4 14h2v4h4v2H4v-6zm16 0h-2v4h-4v2h6v-6zM9 9h6v6H9V9z"/></svg>
        Scan QR
    </a>
</div>

<div class="row g-4 mb-2">
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-left-color: var(--primary)">
            <div class="metric-card-accent" style="background: var(--primary)"></div>
            <div class="metric-top"><span class="metric-label">Total Scan Hari Ini</span><span class="metric-dot" style="background: var(--primary)"></span></div>
            <div class="metric-value">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-left-color: var(--info)">
            <div class="metric-card-accent" style="background: var(--info)"></div>
            <div class="metric-top"><span class="metric-label">Scan Keluar</span><span class="metric-dot" style="background: var(--info)"></span></div>
            <div class="metric-value">{{ $stats['keluar'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-left-color: var(--success)">
            <div class="metric-card-accent" style="background: var(--success)"></div>
            <div class="metric-top"><span class="metric-label">Scan Kembali</span><span class="metric-dot" style="background: var(--success)"></span></div>
            <div class="metric-value">{{ $stats['kembali'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card project-card" style="border-left-color: var(--danger)">
            <div class="metric-card-accent" style="background: var(--danger)"></div>
            <div class="metric-top"><span class="metric-label">Gagal</span><span class="metric-dot" style="background: var(--danger)"></span></div>
            <div class="metric-value">{{ $stats['gagal'] }}</div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <a class="btn btn-primary" href="{{ route('sik.security.scan') }}">Mulai Scan QR</a>
    <a class="btn btn-outline-secondary" href="{{ route('sik.security.history') }}">Riwayat Scan Hari Ini</a>
</div>

@endsection
