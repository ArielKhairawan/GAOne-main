@extends('layouts.app')

@section('title', 'Master Ruangan')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    @can('meeting.create')
    <a class="btn btn-sm" href="{{ route('meeting.rooms.create') }}" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Tambah Ruangan
    </a>
    @endcan
</div>

<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-family: 'Poppins', sans-serif; font-size: 13.5px; width: 100%;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 12%;">Kode</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Nama Ruangan</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Lokasi</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Kapasitas</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Fasilitas</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 12%;">Status</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; text-align: right; width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody style="border-top: none;">
                @forelse($rooms as $room)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s ease;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 600;">
                        {{ $room->kode_ruangan }}
                    </td>
                    <td style="padding: 18px 24px; font-weight: 700; color: #0F172A;">
                        {{ $room->nama_ruangan }}
                    </td>
                    <td style="padding: 18px 24px; color: #475569; font-weight: 500;">
                        {{ $room->lokasi ?? '—' }}
                    </td>
                    <td style="padding: 18px 24px; color: #334155; font-weight: 600;">
                        {{ $room->kapasitas }} orang
                    </td>
                    <td style="padding: 18px 24px; color: #64748B; font-size: 12px; font-weight: 500;">
                        {{ implode(', ', $room->fasilitas ?? []) ?: '—' }}
                    </td>
                    <td style="padding: 18px 24px;">
                        @if($room->status === 'tersedia')
                            <span style="background: rgba(16,185,129,0.1); color: #10B981; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ config('monitoring.meeting_room_statuses')[$room->status] }}
                            </span>
                        @elseif($room->status === 'tidak_aktif')
                            <span style="background: rgba(239,68,68,0.1); color: #EF4444; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ config('monitoring.meeting_room_statuses')[$room->status] }}
                            </span>
                        @else
                            <span style="background: rgba(245,158,11,0.1); color: #F59E0B; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ config('monitoring.meeting_room_statuses')[$room->status] }}
                            </span>
                        @endif
                    </td>
                    <td style="padding: 18px 24px; text-align: right;">
                        <div class="d-flex gap-2 justify-content-end">
                            @can('meeting.edit')
                            <a class="btn btn-sm" href="{{ route('meeting.rooms.edit', $room) }}" style="background: #ffffff; color: #F59E0B; border: 1px solid #FDE68A; font-weight: 600; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                Edit
                            </a>
                            @endcan
                            @can('meeting.delete')
                            <form method="post" action="{{ route('meeting.rooms.destroy', $room) }}" onsubmit="return confirm('Hapus ruangan ini?')" style="display: inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="background: #ffffff; color: #EF4444; border: 1px solid #FEE2E2; font-weight: 600; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                    Hapus
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 64px 24px; color: #94A3B8;">
                        <div style="font-weight: 600; font-size: 14px; color: #64748B;">Belum ada data ruangan</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4" style="font-family: 'Poppins', sans-serif;">
    {{ $rooms->links() }}
</div>

@endsection
