@extends('layouts.app')

@section('title', 'Monitoring Kebersihan WC')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    <div class="d-flex align-items-center gap-2">
        @can('toilet.export')
        <a class="btn btn-sm" href="{{ route('toilet.export.pdf', $filters) }}" style="background: rgba(239, 68, 68, 0.1); color: #EF4444; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm10 5.5h1v-3h-1v3z"/></svg>
            Unduh PDF
        </a>
        <a class="btn btn-sm" href="{{ route('toilet.export.excel', $filters) }}" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
            Unduh Excel
        </a>
        @endcan
        @can('toilet.create')
        <a class="btn btn-sm" href="{{ route('toilet.create') }}" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Inspeksi
        </a>
        @endcan
    </div>
</div>

<form method="get" class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 20px 24px; font-family: 'Poppins', sans-serif;">
    <div class="row g-3 align-items-center">
        <div class="col-md-3">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Tanggal Awal</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ $filters['date_from'] ?? '' }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
        </div>
        <div class="col-md-3">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Tanggal Akhir</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ $filters['date_to'] ?? '' }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
        </div>
        <div class="col-md-4">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Lokasi / Toilet</label>
            <select name="toilet_location" class="form-select form-select-sm" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px;">
                <option value="">— Semua Lokasi —</option>
                @foreach(config('monitoring.toilet_locations', []) as $loc)
                    <option value="{{ $loc }}" @selected(($filters['toilet_location'] ?? '') === $loc)>{{ $loc }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 mt-4 pt-2">
            <button class="btn btn-sm px-4" style="background: #475569; color: #ffffff; border: none; font-weight: 600; height: 36px; border-radius: 6px; font-size: 13px; width: 100%;">Terapkan Filter</button>
        </div>
    </div>
</form>

<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-family: 'Poppins', sans-serif; font-size: 13.5px; width: 100%;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 15%;">Tanggal</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Lokasi WC</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Inspektor</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Kondisi Umum</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; text-align: right; width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody style="border-top: none;">
                @forelse($inspections as $ins)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s ease;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 500;">
                        {{ $ins->inspected_at->format('d M Y H:i') }}
                    </td>
                    <td style="padding: 18px 24px; font-weight: 700; color: #0F172A;">
                        {{ $ins->toilet_location }}
                    </td>
                    <td style="padding: 18px 24px; color: #475569; font-weight: 500;">
                        {{ $ins->inspector->name ?? '—' }}
                    </td>
                    <td style="padding: 18px 24px; color: #334155;">
                        @php
                            $dirtyCount = $ins->items->where('status', 'kotor')->count();
                        @endphp
                        @if($dirtyCount > 0)
                            <span style="background: rgba(239,68,68,0.1); color: #EF4444; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                {{ $dirtyCount }} Item Kotor
                            </span>
                        @else
                            <span style="background: rgba(16,185,129,0.1); color: #10B981; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                Semua Bersih
                            </span>
                        @endif
                    </td>
                    <td style="padding: 18px 24px; text-align: right;">
                        <div class="d-flex gap-2 justify-content-end">
                            @can('toilet.edit')
                            <a class="btn btn-sm" href="{{ route('toilet.edit', $ins) }}" style="background: #ffffff; color: #F59E0B; border: 1px solid #FDE68A; font-weight: 600; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                Edit
                            </a>
                            @endcan
                            @can('toilet.delete')
                            <form method="post" action="{{ route('toilet.destroy', $ins) }}" onsubmit="return confirm('Hapus data inspeksi ini?')" style="display: inline;">
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
                    <td colspan="5" style="text-align: center; padding: 64px 24px; color: #94A3B8;">
                        <div style="width: 56px; height: 56px; background: rgba(59, 130, 246, 0.1); color: #3B82F6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x=\"3\" y=\"3\" width=\"18\" height=\"18\" rx=\"2\" ry=\"2\"></rect><circle cx=\"8.5\" cy=\"8.5\" r=\"1.5\"></circle><polyline points=\"21 15 16 10 5 21\"></polyline></svg>
                        </div>
                        <div style="font-weight: 600; font-size: 14px; color: #64748B;">Data Kosong</div>
                        <div style="font-size: 12.5px; color: #94A3B8; margin-top: 4px;">Belum ada inspeksi yang sesuai dengan kriteria pencarian.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4" style="font-family: 'Poppins', sans-serif;">
    {{ $inspections->links() }}
</div>

@endsection
