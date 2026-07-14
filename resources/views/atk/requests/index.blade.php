@extends('layouts.app')

@section('title', 'Permintaan ATK')

@section('content')

<!-- Header Dashboard / Index (Tombol Aksi di Sebelah Kanan) -->
<div class="d-flex justify-content-end align-items-center mb-4">
    @can('atk.create')
    <a class="btn" href="{{ route('atk.requests.create') }}" style="background: #3B82F6; color: #ffffff; font-weight: 600; padding: 10px 20px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2); border: none;">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Buat Permintaan
    </a>
    @endcan
</div>

<!-- Container Tabel -->
<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-family: 'Poppins', sans-serif; font-size: 13.5px; width: 100%;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 15%;">Tanggal</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Pemohon</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Departemen</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; text-align: center;">Jumlah Item</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Status</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; text-align: right; width: 12%;">Aksi</th>
                </tr>
            </thead>
            <tbody style="border-top: none;">
                @forelse($requests as $r)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s ease;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 500;">
                        {{ $r->created_at->format('d M Y') }}
                    </td>
                    <td style="padding: 18px 24px; font-weight: 700; color: #0F172A;">
                        {{ $r->requester->name ?? '—' }}
                    </td>
                    <td style="padding: 18px 24px; color: #334155; font-weight: 500;">
                        {{ $r->department }}
                    </td>
                    <td style="padding: 18px 24px; color: #334155; font-weight: 600; text-align: center;">
                        <span style="background: #F1F5F9; color: #475569; padding: 4px 10px; border-radius: 6px; font-size: 12px;">
                            {{ $r->items->count() }} Item
                        </span>
                    </td>
                    <td style="padding: 18px 24px;">
                        @if($r->status === 'approved')
                            <span style="background: rgba(16,185,129,0.1); color: #10B981; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ $statusLabels[$r->status] ?? $r->status }}
                            </span>
                        @elseif($r->status === 'rejected')
                            <span style="background: rgba(239,68,68,0.1); color: #EF4444; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ $statusLabels[$r->status] ?? $r->status }}
                            </span>
                        @else
                            <span style="background: rgba(245,158,11,0.1); color: #F59E0B; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ $statusLabels[$r->status] ?? $r->status }}
                            </span>
                        @endif
                    </td>
                    <td style="padding: 18px 24px; text-align: right;">
                        <a class="btn btn-sm" href="{{ route('atk.requests.show', $r) }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 64px 24px; color: #94A3B8;">
                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; color: #CBD5E1;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <div style="font-weight: 600; font-size: 14px; color: #64748B;">Belum ada pengajuan permintaan ATK</div>
                        <div style="font-size: 12.5px; color: #94A3B8; margin-top: 4px;">Setiap pengajuan baru oleh staf akan terdaftar langsung di sini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Navigasi Pagination -->
<div class="d-flex justify-content-between align-items-center mt-4">
    <div style="font-family: 'Poppins', sans-serif;">
        {{ $requests->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
