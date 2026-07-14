@extends('layouts.app')

@section('title', 'Booking Ruang Meeting')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    @can('meeting.create')
    <a class="btn btn-sm" href="{{ route('meeting.bookings.create') }}" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Buat Booking
    </a>
    @endcan
</div>

<form method="get" class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px 24px; font-family: 'Poppins', sans-serif;">
    <div class="row g-3 align-items-center">
        <div class="col-md-3">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
        </div>
        <div class="col-md-3">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
        </div>
        <div class="col-md-3">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Ruangan</label>
            <select name="meeting_room_id" class="form-select form-select-sm" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
                <option value="">— Semua Ruangan —</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" @selected(($filters['meeting_room_id'] ?? '') == $room->id)>{{ $room->nama_ruangan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mt-4 pt-2">
            <button class="btn btn-sm px-4" style="background: #475569; color: #ffffff; border: none; font-weight: 600; height: 36px; border-radius: 6px; font-size: 13px; width: 100%;">Terapkan Filter</button>
        </div>
    </div>
</form>

<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-family: 'Poppins', sans-serif; font-size: 13.5px; width: 100%;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Tanggal</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Jam</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Ruangan</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Kegiatan</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Pemohon</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Peserta</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 12%;">Status</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; text-align: right; width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody style="border-top: none;">
                @forelse($bookings as $b)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s ease;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 500;">
                        {{ $b->tanggal->format('d M Y') }}
                    </td>
                    <td style="padding: 18px 24px; color: #475569; font-weight: 500;">
                        {{ $b->jam_mulai }} - {{ $b->jam_selesai }}
                    </td>
                    <td style="padding: 18px 24px; font-weight: 700; color: #0F172A;">
                        {{ $b->room->nama_ruangan ?? '—' }}
                    </td>
                    <td style="padding: 18px 24px; color: #334155; font-weight: 500;">
                        {{ $b->nama_kegiatan }}
                    </td>
                    <td style="padding: 18px 24px; color: #475569;">
                        {{ $b->requester->name ?? '—' }}
                    </td>
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 600;">
                        {{ $b->jumlah_peserta }} orang
                    </td>
                    <td style="padding: 18px 24px;">
                        @if(in_array($b->status, ['approved', 'selesai']))
                            <span style="background: rgba(16,185,129,0.1); color: #10B981; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ $statusLabels[$b->status] ?? $b->status }}
                            </span>
                        @elseif($b->status === 'rejected')
                            <span style="background: rgba(239,68,68,0.1); color: #EF4444; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ $statusLabels[$b->status] ?? $b->status }}
                            </span>
                        @else
                            <span style="background: rgba(245,158,11,0.1); color: #F59E0B; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ $statusLabels[$b->status] ?? $b->status }}
                            </span>
                        @endif
                    </td>
                    <td style="padding: 18px 24px; text-align: right;">
                        <a class="btn btn-sm" href="{{ route('meeting.bookings.show', $b) }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            Lihat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 64px 24px; color: #94A3B8;">
                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; color: #CBD5E1;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <div style="font-weight: 600; font-size: 14px; color: #64748B;">Belum ada data booking</div>
                        <div style="font-size: 12.5px; color: #94A3B8; margin-top: 4px;">Riwayat pemesanan ruang meeting akan muncul di sini.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4" style="font-family: 'Poppins', sans-serif;">
    {{ $bookings->links() }}
</div>

@endsection
