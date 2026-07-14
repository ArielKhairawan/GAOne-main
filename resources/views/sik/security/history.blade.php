@extends('layouts.app')

@section('title', 'Riwayat Scan Hari Ini')

@section('content')

<!-- Header Form (Tombol Kembali di Sebelah Kanan) -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('sik.security.dashboard') }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Dashboard Security
    </a>
</div>

<!-- Grid Metrik Statistik Riwayat -->
<div class="row g-4 mb-4" style="font-family: 'Poppins', sans-serif;">
    <div class="col-xl-3 col-md-6">
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 18px; border-left: 4px solid #3B82F6;">
            <div style="font-size: 11px; font-weight: 600; color: #64748B;">Total Riwayat</div>
            <div style="font-size: 24px; font-weight: 700; color: #0F172A; margin-top: 6px;">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 18px; border-left: 4px solid #0EA5E9;">
            <div style="font-size: 11px; font-weight: 600; color: #64748B;">Keluar</div>
            <div style="font-size: 24px; font-weight: 700; color: #0F172A; margin-top: 6px;">{{ $stats['keluar'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 18px; border-left: 4px solid #10B981;">
            <div style="font-size: 11px; font-weight: 600; color: #64748B;">Kembali</div>
            <div style="font-size: 24px; font-weight: 700; color: #0F172A; margin-top: 6px;">{{ $stats['kembali'] }}</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 18px; border-left: 4px solid #EF4444;">
            <div style="font-size: 11px; font-weight: 600; color: #64748B;">Gagal</div>
            <div style="font-size: 24px; font-weight: 700; color: #0F172A; margin-top: 6px;">{{ $stats['gagal'] }}</div>
        </div>
    </div>
</div>

<!-- Tabel Riwayat Transaksi -->
<div class="card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); font-family: 'Poppins', sans-serif; overflow: hidden;">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-size: 13px;">
            <thead style="background: #F8FAFC;">
                <tr>
                    <th style="padding: 16px 20px; font-weight: 600; color: #475569; border-bottom: 1px solid #E2E8F0;">Waktu Scan</th>
                    <th style="padding: 16px 20px; font-weight: 600; color: #475569; border-bottom: 1px solid #E2E8F0;">Nomor SIK</th>
                    <th style="padding: 16px 20px; font-weight: 600; color: #475569; border-bottom: 1px solid #E2E8F0;">Nama Karyawan</th>
                    <th style="padding: 16px 20px; font-weight: 600; color: #475569; border-bottom: 1px solid #E2E8F0;">Tipe</th>
                    <th style="padding: 16px 20px; font-weight: 600; color: #475569; border-bottom: 1px solid #E2E8F0;">Petugas Security</th>
                    <th style="padding: 16px 20px; font-weight: 600; color: #475569; border-bottom: 1px solid #E2E8F0;">Hasil Scan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scans as $scan)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 16px 20px; color: #64748B;">{{ $scan->scanned_at->format('d M Y H:i:s') }}</td>
                    <td style="padding: 16px 20px; font-weight: 600; color: #0F172A;">{{ $scan->suratIzinKeluar?->nomor_sik ?? '—' }}</td>
                    <td style="padding: 16px 20px; color: #334155; font-weight: 500;">{{ $scan->suratIzinKeluar?->user?->name ?? '—' }}</td>
                    <td style="padding: 16px 20px;">
                        @if($scan->type === 'keluar')
                            <span class="badge" style="background: #E0F2FE; color: #0369A1; font-weight: 600; font-size: 11px; padding: 6px 10px; border-radius: 6px;">Scan Keluar</span>
                        @elseif($scan->type === 'kembali')
                            <span class="badge" style="background: #D1FAE5; color: #065F46; font-weight: 600; font-size: 11px; padding: 6px 10px; border-radius: 6px;">Scan Kembali</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td style="padding: 16px 20px; color: #475569;">{{ $scan->security?->name ?? '—' }}</td>
                    <td style="padding: 16px 20px;">
                        @if($scan->is_success)
                            <span class="badge" style="background: #DEF7EC; color: #03543F; font-size: 11px; padding: 6px 10px; border-radius: 6px; font-weight: 600;">Berhasil</span>
                        @else
                            <span class="badge" style="background: #FDE8E8; color: #9B1C1C; font-size: 11px; padding: 6px 10px; border-radius: 6px; font-weight: 500; white-space: normal; line-height: 1.4; display: inline-block; max-width: 200px;">
                                {{ $scan->message }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 56px 20px; color: #94A3B8; font-size: 14px;">
                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" style="color: #CBD5E1; margin-bottom: 12px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <div>Belum ada aktivitas scan terekam hari ini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination Grid -->
<div class="mt-4 d-flex justify-content-center" style="font-family: 'Poppins', sans-serif;">
    {{ $scans->links() }}
</div>

@endsection
