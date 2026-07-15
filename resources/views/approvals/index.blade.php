@extends('layouts.app')

@section('title', 'Persetujuan')
@section('page-title', 'Persetujuan Dokumen')
@section('page-subtitle', 'Pantau dan tindaklanjuti permintaan yang membutuhkan persetujuan')

@section('content')

<div class="metric-card mb-4" style="padding: 0; overflow: hidden; border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">

    <div style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
        <div>
            <div style="font-weight: 700; font-size: 16px; color: #0F172A;">Menunggu Persetujuan</div>
            <div style="font-size: 13px; color: #64748B; margin-top: 2px;">Dokumen yang membutuhkan tindakan Anda segera</div>
        </div>

        @if($pending->total() > 0)
            <div style="background: rgba(245, 158, 11, 0.15); color: #D97706; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                <span style="width: 8px; height: 8px; background: #D97706; border-radius: 50%;"></span>
                {{ $pending->total() }} Menunggu
            </div>
        @else
            <div style="background: rgba(16, 185, 129, 0.15); color: #10B981; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Semua Tuntas
            </div>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0" style="width: 100%; border-collapse: collapse;">
            <thead style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                <tr>
                    <th style="padding: 14px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">ID Dokumen</th>
                    <th style="padding: 14px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Modul</th>
                    <th style="padding: 14px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                    <th style="padding: 14px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Diajukan</th>
                    <th class="text-end" style="padding: 14px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pending as $approval)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 16px 24px; font-size: 13.5px; color: #64748B; font-weight: 500; font-variant-numeric: tabular-nums;">
                        #{{ str_pad($approval->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td style="padding: 16px 24px; font-weight: 600; font-size: 14px; color: #0F172A;">
                        {{ class_basename($approval->approvable_type) }}
                    </td>
                    <td style="padding: 16px 24px;">
                        <span style="background: rgba(245, 158, 11, 0.15); color: #D97706; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">Menunggu</span>
                    </td>
                    <td style="padding: 16px 24px; color: #64748B; font-size: 13px;">
                        {{ optional($approval->submitted_at)->diffForHumans() ?? '—' }}
                    </td>
                    <td class="text-end" style="padding: 16px 24px;">
                        <div class="d-flex gap-2 justify-content-end">
                            <form method="POST" action="{{ route('approvals.act', $approval) }}" class="m-0">
                                @csrf <input type="hidden" name="action" value="approve">
                                <button class="btn btn-sm" style="background: rgba(16,185,129,.1); color: #10B981; border: none; font-weight: 600; padding: 6px 14px; border-radius: 8px;">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('approvals.act', $approval) }}" class="m-0">
                                @csrf <input type="hidden" name="action" value="revision">
                                <button class="btn btn-sm" style="background: rgba(245,158,11,.1); color: #D97706; border: none; font-weight: 600; padding: 6px 14px; border-radius: 8px;">Revisi</button>
                            </form>
                            <form method="POST" action="{{ route('approvals.act', $approval) }}" class="m-0">
                                @csrf <input type="hidden" name="action" value="reject">
                                <button class="btn btn-sm" style="background: rgba(225,29,72,.1); color: #E11D48; border: none; font-weight: 600; padding: 6px 14px; border-radius: 8px;">Tolak</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding: 64px 24px;">
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="width: 56px; height: 56px; background: rgba(16, 185, 129, 0.1); color: #10B981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </div>
                            <div style="font-size: 16px; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Pekerjaan Selesai!</div>
                            <div style="font-size: 13px; color: #64748B;">Tidak ada dokumen yang perlu disetujui saat ini. Bersantai sejenak! ☕</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mb-5">{{ $pending->links() }}</div>

@if($sikPending)
<div class="metric-card mb-4" style="padding: 0; overflow: hidden; border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">

    <div style="padding: 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
        <div>
            <div style="font-weight: 700; font-size: 16px; color: #0F172A;">Menunggu Persetujuan SIK</div>
            <div style="font-size: 13px; color: #64748B; margin-top: 2px;">Pengajuan Surat Izin Keluar yang menunggu persetujuan Anda</div>
        </div>

        @if($sikPending->total() > 0)
            <div style="background: rgba(245, 158, 11, 0.15); color: #D97706; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                <span style="width: 8px; height: 8px; background: #D97706; border-radius: 50%;"></span>
                {{ $sikPending->total() }} Menunggu
            </div>
        @else
            <div style="background: rgba(16, 185, 129, 0.15); color: #10B981; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Semua Tuntas
            </div>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0" style="width: 100%; border-collapse: collapse;">
            <thead style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                <tr>
                    <th style="padding: 14px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Nama</th>
                    <th style="padding: 14px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Departemen</th>
                    <th style="padding: 14px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Jenis Izin</th>
                    <th style="padding: 14px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Diajukan</th>
                    <th class="text-end" style="padding: 14px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sikPending as $sik)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 16px 24px; font-weight: 600; font-size: 14px; color: #0F172A;">
                        {{ $sik->user->name }}
                    </td>
                    <td style="padding: 16px 24px; color: #64748B; font-size: 13px;">
                        {{ $sik->department ?: '—' }}
                    </td>
                    <td style="padding: 16px 24px;">
                        <span style="background: #F1F5F9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">{{ $sik->jenis_izin_label }}</span>
                    </td>
                    <td style="padding: 16px 24px; color: #64748B; font-size: 13px;">
                        {{ $sik->created_at->diffForHumans() }}
                    </td>
                    <td class="text-end" style="padding: 16px 24px;">
                        <a href="{{ route('sik.approvals.show', $sik) }}" class="btn btn-sm" style="background: #3B82F6; color: #ffffff; font-weight: 600; padding: 6px 14px; border-radius: 8px;">Proses</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding: 48px 24px; color: #64748B; font-size: 13px;">Tidak ada pengajuan SIK yang menunggu persetujuan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mb-5">{{ $sikPending->links() }}</div>
@endif



<div class="metric-card" style="padding: 0; overflow: hidden; border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">

    <div style="padding: 24px; border-bottom: 1px solid var(--border); background: #ffffff;">
        <div style="font-weight: 700; font-size: 16px; color: #0F172A;">Riwayat Keputusan</div>
        <div style="font-size: 13px; color: #64748B; margin-top: 2px;">Jejak persetujuan, penolakan, dan revisi sebelumnya</div>
    </div>

    <div class="list-group" style="border-radius: 0;">
        @forelse($history as $item)
        <div class="list-group-item" style="padding: 16px 24px; border-bottom: 1px solid #f1f5f9; border-top: none; border-left: none; border-right: none; display: flex; justify-content: space-between; align-items: center; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">

            <div class="d-flex align-items-center gap-3">
                <div style="width: 42px; height: 42px; border-radius: 12px; background: #F1F5F9; display: flex; align-items: center; justify-content: center; color: #475569; font-weight: 700; font-size: 13px;">
                    #{{ str_pad($item->id, 2, '0', STR_PAD_LEFT) }}
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 14px; color: #0F172A;">{{ class_basename($item->approvable_type ?? 'Item') }}</div>
                    <div style="font-size: 12px; color: #64748B; margin-top: 2px;">Diproses pada: <span style="font-weight: 500;">{{ optional($item->completed_at)->format('d M Y, H:i') ?? '—' }}</span></div>
                </div>
            </div>

            <div>
                @if($item->status === 'approved')
                    <span style="background: rgba(16,185,129,.15); color: #10B981; padding: 6px 12px; border-radius: 8px; font-size: 11.5px; font-weight: 700;">Disetujui</span>
                @elseif($item->status === 'rejected')
                    <span style="background: rgba(225,29,72,.15); color: #E11D48; padding: 6px 12px; border-radius: 8px; font-size: 11.5px; font-weight: 700;">Ditolak</span>
                @elseif($item->status === 'revision')
                    <span style="background: rgba(245,158,11,.15); color: #D97706; padding: 6px 12px; border-radius: 8px; font-size: 11.5px; font-weight: 700;">Direvisi</span>
                @else
                    <span style="background: #F1F5F9; color: #475569; padding: 6px 12px; border-radius: 8px; font-size: 11.5px; font-weight: 700;">{{ ucfirst($item->status) }}</span>
                @endif
            </div>

        </div>
        @empty
        <div style="padding: 48px 24px; text-align: center; color: #64748B; font-size: 14px;">Belum ada riwayat persetujuan tercatat.</div>
        @endforelse
    </div>
</div>

@endsection
