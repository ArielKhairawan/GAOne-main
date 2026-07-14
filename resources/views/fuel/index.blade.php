@extends('layouts.app')

@section('title', 'Survei Kepuasan')

@section('content')

@if($pending->isNotEmpty())
<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
        <div style="font-size: 14px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Menunggu Penilaian Anda</div>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-family: 'Poppins', sans-serif; font-size: 13.5px; width: 100%;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Modul</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Tanggal</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; text-align: right; width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody style="border-top: none;">
                @foreach($pending as $survey)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s ease;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 18px 24px; font-weight: 700; color: #0F172A;">
                        {{ $moduleLabels[$survey->service_type] ?? $survey->service_type }}
                    </td>
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 500;">
                        {{ $survey->sent_at?->format('d M Y') }}
                    </td>
                    <td style="padding: 18px 24px; text-align: right;">
                        <a class="btn btn-sm" href="{{ route('csat.show', $survey) }}" style="background: #3B82F6; color: #ffffff; font-weight: 600; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; border: none; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
                            Beri Rating
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="alert mb-4" style="background: rgba(59,130,246,0.05); color: #3B82F6; border: 1px solid rgba(59,130,246,0.1); border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 13.5px; font-weight: 500;">
    Tidak ada survei yang menunggu penilaian Anda saat ini.
</div>
@endif

<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
        <div style="font-size: 14px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Riwayat Survei Anda</div>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-family: 'Poppins', sans-serif; font-size: 13.5px; width: 100%;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 25%;">Modul</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 20%;">Rating</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Komentar</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 15%;">Tanggal</th>
                </tr>
            </thead>
            <tbody style="border-top: none;">
                @forelse($history as $survey)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s ease;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 18px 24px; font-weight: 700; color: #0F172A;">
                        {{ $moduleLabels[$survey->service_type] ?? $survey->service_type }}
                    </td>
                    <td style="padding: 18px 24px; font-weight: 600; color: #F59E0B;">
                        {{ $survey->response->satisfaction_score ?? '—' }}/5
                        <span class="text-muted small font-medium" style="color: #64748B !important; font-weight: 500;">— {{ $ratingLabels[$survey->response->satisfaction_score] ?? '' }}</span>
                    </td>
                    <td style="padding: 18px 24px; color: #334155;">
                        {{ $survey->response->comments ?? '—' }}
                    </td>
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 500;">
                        {{ $survey->completed_at?->format('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 48px 24px; color: #94A3B8;">
                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; color: #CBD5E1;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <div style="font-weight: 600; font-size: 14px; color: #64748B;">Belum ada riwayat survei</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
