@extends('layouts.app')

@section('title', 'Dashboard Scan')

@section('content')

<!-- Header Form (Tombol Navigasi di Sebelah Kanan) -->
<div class="d-flex justify-content-end align-items-center mb-4" style="font-family: 'Poppins', sans-serif;">
    <a class="btn btn-sm" href="{{ route('sik.security.scan') }}" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15); transition: all 0.2s;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M4 4h6v2H6v4H4V4zm10 0h6v6h-2V6h-4V4zM4 14h2v4h4v2H4v-6zm16 0h-2v4h-4v2h6v-6zM9 9h6v6H9V9z"/></svg>
        Mulai Scan QR
    </a>
</div>

<!-- Informasi Tanggal & Jam Hari Ini -->
<div class="mb-4" style="font-family: 'Poppins', sans-serif;">
    <span style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal Aktivitas</span>
    <h4 style="font-size: 18px; font-weight: 700; color: #0F172A; margin: 2px 0 0 0;">{{ now()->translatedFormat('l, d F Y') }}</h4>
</div>

<!-- Grid Metrik Statistik -->
<div class="row g-4 mb-4" style="font-family: 'Poppins', sans-serif;">
    <div class="col-xl-3 col-md-6">
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px; border-left: 4px solid #3B82F6;">
            <div style="font-size: 12px; font-weight: 600; color: #64748B;">Total Scan Hari Ini</div>
            <div style="font-size: 28px; font-weight: 700; color: #0F172A; margin-top: 8px;">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px; border-left: 4px solid #0EA5E9;">
            <div style="font-size: 12px; font-weight: 600; color: #64748B;">Scan Keluar</div>
            <div style="font-size: 28px; font-weight: 700; color: #0F172A; margin-top: 8px;">{{ $stats['keluar'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px; border-left: 4px solid #10B981;">
            <div style="font-size: 12px; font-weight: 600; color: #64748B;">Scan Kembali</div>
            <div style="font-size: 28px; font-weight: 700; color: #0F172A; margin-top: 8px;">{{ $stats['kembali'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px; border-left: 4px solid #EF4444;">
            <div style="font-size: 12px; font-weight: 600; color: #64748B;">Gagal / Ditolak</div>
            <div style="font-size: 28px; font-weight: 700; color: #0F172A; margin-top: 8px;">{{ $stats['gagal'] }}</div>
        </div>
    </div>
</div>

<!-- Tombol Navigasi Alternatif Bawah -->
<div class="d-flex align-items-center gap-2" style="font-family: 'Poppins', sans-serif;">
    <a class="btn btn-sm" href="{{ route('sik.security.scan') }}" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 12px 24px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
        Mulai Scan QR
    </a>
    <a class="btn btn-sm" href="{{ route('sik.security.history') }}" style="background: #F1F5F9; color: #475569; border: none; font-weight: 600; padding: 12px 24px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;">
        Riwayat Scan Hari Ini
    </a>
</div>

@endsection
