@extends('layouts.app')

@section('title', 'Laporan SIK')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    @can('sik.export')
    <div class="d-flex align-items-center gap-2">
        <a class="btn btn-sm" href="{{ route('sik.laporan.export.pdf', $filters) }}" style="background: rgba(239, 68, 68, 0.1); color: #EF4444; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/></svg>
            Export PDF
        </a>
        <a class="btn btn-sm" href="{{ route('sik.laporan.export.excel', $filters) }}" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
            Export Excel
        </a>
    </div>
    @endcan
</div>

<div class="row g-3 mb-4" style="font-family: 'Poppins', sans-serif;">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="metric-card" style="border-radius: 14px; background: #ffffff; border: 1px solid var(--border); border-left: 4px solid #3B82F6; box-shadow: 0 1px 3px rgba(0,0,0,0.01); padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Total</div>
            <div style="font-size: 22px; font-weight: 700; color: #0F172A; margin-top: 4px;">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="metric-card" style="border-radius: 14px; background: #ffffff; border: 1px solid var(--border); border-left: 4px solid #F59E0B; box-shadow: 0 1px 3px rgba(0,0,0,0.01); padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Pending</div>
            <div style="font-size: 22px; font-weight: 700; color: #F59E0B; margin-top: 4px;">{{ $stats['pending_approval'] }}</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="metric-card" style="border-radius: 14px; background: #ffffff; border: 1px solid var(--border); border-left: 4px solid #10B981; box-shadow: 0 1px 3px rgba(0,0,0,0.01); padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Approved</div>
            <div style="font-size: 22px; font-weight: 700; color: #10B981; margin-top: 4px;">{{ $stats['approved'] }}</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="metric-card" style="border-radius: 14px; background: #ffffff; border: 1px solid var(--border); border-left: 4px solid #06B6D4; box-shadow: 0 1px 3px rgba(0,0,0,0.01); padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Sedang Keluar</div>
            <div style="font-size: 22px; font-weight: 700; color: #06B6D4; margin-top: 4px;">{{ $stats['sedang_keluar'] }}</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="metric-card" style="border-radius: 14px; background: #ffffff; border: 1px solid var(--border); border-left: 4px solid #64748B; box-shadow: 0 1px 3px rgba(0,0,0,0.01); padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Completed</div>
            <div style="font-size: 22px; font-weight: 700; color: #64748B; margin-top: 4px;">{{ $stats['completed'] }}</div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="metric-card" style="border-radius: 14px; background: #ffffff; border: 1px solid var(--border); border-left: 4px solid #EF4444; box-shadow: 0 1px 3px rgba(0,0,0,0.01); padding: 16px 20px;">
            <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Rejected</div>
            <div style="font-size: 22px; font-weight: 700; color: #EF4444; margin-top: 4px;">{{ $stats['rejected'] }}</div>
        </div>
    </div>
</div>

<form method="get" class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px 24px; font-family: 'Poppins', sans-serif;">
    <div class="row g-3">
        <div class="col-md-2">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Tanggal Awal</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Tanggal Akhir</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Departemen</label>
            <select name="department" class="form-select form-select-sm" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
                <option value="">— Semua —</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" @selected(($filters['department'] ?? '') === $dept)>{{ $dept }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Status</label>
            <select name="status" class="form-select form-select-sm" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
                <option value="">— Semua —</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Jenis Izin</label>
            <select name="jenis_izin" class="form-select form-select-sm" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
                <option value="">— Semua —</option>
                @foreach($jenisIzinOptions as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['jenis_izin'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Karyawan</label>
            <select name="employee_id" class="form-select form-select-sm" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
                <option value="">— Semua —</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected((string) ($filters['employee_id'] ?? '') === (string) $emp->id)>{{ $emp->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 d-flex justify-content-end gap-2 mt-3">
            @if(request()->anyFilled(['date_from', 'date_to', 'department', 'status', 'jenis_izin', 'employee_id']))
                <a class="btn btn-sm btn-outline-secondary d-flex align-items-center justify-content-center" href="{{ route('sik.laporan') }}" style="height: 36px; border-radius: 6px; font-size: 13px; font-weight: 500; padding: 0 16px;">Reset</a>
            @endif
            <button class="btn btn-sm px-4" style="background: #475569; color: #ffffff; border: none; font-weight: 600; height: 36px; border-radius: 6px; font-size: 13px;">Terapkan Filter</button>
        </div>
    </div>
</form>

<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-family: 'Poppins', sans-serif; font-size: 13.5px; width: 100%;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 15%;">Nomor SIK</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Nama Karyawan</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Departemen</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Jenis Izin</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Jam Keluar Rencana</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 12%;">Status</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; text-align: right; width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody style="border-top: none;">
                @forelse($items as $item)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s ease;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 600;">
                        {{ $item->nomor_sik ?: '—' }}
                    </td>
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
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 500;">
                        {{ $item->jam_keluar_rencana->format('d M Y H:i') }}
                    </td>
                    <td style="padding: 18px 24px;">
                        @if(in_array($item->status, ['approved', 'completed']))
                            <span style="background: rgba(16,185,129,0.1); color: #10B981; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ $item->status_label }}
                            </span>
                        @elseif($item->status === 'rejected')
                            <span style="background: rgba(239,68,68,0.1); color: #EF4444; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ $item->status_label }}
                            </span>
                        @else
                            <span style="background: rgba(245,158,11,0.1); color: #F59E0B; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">
                                {{ $item->status_label }}
                            </span>
                        @endif
                    </td>
                    <td style="padding: 18px 24px; text-align: right;">
                        <a class="btn btn-sm" href="{{ route('sik.show', $item) }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            Lihat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 64px 24px; color: #94A3B8;">
                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; color: #CBD5E1;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <div style="font-weight: 600; font-size: 14px; color: #64748B;">Tidak ada data untuk filter ini</div>
                        <div style="font-size: 12.5px; color: #94A3B8; margin-top: 4px;">Cobalah untuk mengubah kriteria filter pencarian Anda di atas.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-4">
    <div style="font-family: 'Poppins', sans-serif;">
        {{ $items->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection
