@extends('layouts.app')

@section('title', 'Approval SIK')

@section('content')

<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-family: 'Poppins', sans-serif; font-size: 13.5px; width: 100%;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Nama</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Departemen</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Jenis Izin</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Keperluan</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Jam Keluar Rencana</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Diajukan</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; text-align: right; width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody style="border-top: none;">
                @forelse($items as $item)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s ease;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 18px 24px; font-weight: 700; color: #0F172A;">
                        {{ $item->user->name }}
                    </td>
                    <td style="padding: 18px 24px; color: #475569; font-weight: 500;">
                        {{ $item->department ?: '—' }}
                    </td>
                    <td style="padding: 18px 24px; color: #334155;">
                        <span style="background: #F1F5F9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                            {{ $item->jenis_izin_label }}
                        </span>
                    </td>
                    <td style="padding: 18px 24px; color: #334155; max-width: 260px; white-space: normal;">
                        {{ \Illuminate\Support\Str::limit($item->keperluan, 60) }}
                    </td>
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 500;">
                        {{ $item->jam_keluar_rencana->format('d M Y H:i') }}
                    </td>
                    <td style="padding: 18px 24px; color: #64748B;">
                        {{ $item->created_at->format('d M Y H:i') }}
                    </td>
                    <td style="padding: 18px 24px; text-align: right;">
                        <a class="btn btn-sm" href="{{ route('sik.approvals.show', $item) }}" style="background: #3B82F6; color: #ffffff; font-weight: 600; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; border: none; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15); transition: all 0.2s;">
                            Proses
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 64px 24px; color: #94A3B8;">
                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; color: #CBD5E1;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <div style="font-weight: 600; font-size: 14px; color: #64748B;">Tidak ada pengajuan menanti</div>
                        <div style="font-size: 12.5px; color: #94A3B8; margin-top: 4px;">Saat ini tidak ada permohonan Surat Izin Keluar yang memerlukan persetujuan Anda.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $items->links() }}
</div>

@endsection
