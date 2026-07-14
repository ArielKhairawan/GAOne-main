@extends('layouts.app')

@section('title', 'Monitoring Kendaraan')
@section('page-title', 'Monitoring Kendaraan')
@section('page-subtitle', 'Total ' . $vehicles->total() . ' kendaraan terdaftar dalam sistem.')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    @can('vehicle.create')
    <a class="btn btn-sm" href="{{ route('vehicle.create') }}" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 8px 16px; border-radius: 8px; display: flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Tambah Kendaraan
    </a>
    @endcan
</div>

<div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 24px;">
    <div style="flex: 1 1 220px; min-width: 0;">
        <div class="metric-card" style="padding: 20px; background: #ffffff; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="font-size: 13px; font-weight: 600; color: #64748B; margin-bottom: 12px;">Total Unit</div>
            <div style="font-size: 26px; font-weight: 700; color: #0F172A; line-height: 1.2;">{{ $stats['total_unit'] }} <span style="font-size: 14px; font-weight: 600; color: #64748B;">Kendaraan</span></div>
        </div>
    </div>
    <div style="flex: 1 1 220px; min-width: 0;">
        <div class="metric-card" style="padding: 20px; background: #ffffff; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="font-size: 13px; font-weight: 600; color: #64748B; margin-bottom: 12px;">Unit Aktif</div>
            <div style="font-size: 26px; font-weight: 700; color: #10B981; line-height: 1.2;">{{ $stats['unit_aktif'] }} <span style="font-size: 14px; font-weight: 600; color: #64748B;">Beroperasi</span></div>
        </div>
    </div>
    <div style="flex: 1 1 220px; min-width: 0;">
        <div class="metric-card" style="padding: 20px; background: #ffffff; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="font-size: 13px; font-weight: 600; color: #64748B; margin-bottom: 12px;">Unit Servis</div>
            <div style="font-size: 26px; font-weight: 700; color: #D97706; line-height: 1.2;">{{ $stats['unit_servis'] }} <span style="font-size: 14px; font-weight: 600; color: #64748B;">Perbaikan</span></div>
        </div>
    </div>
    <div style="flex: 1 1 220px; min-width: 0;">
        <div class="metric-card" style="padding: 20px; background: #ffffff; border: 1px solid var(--border); border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="font-size: 13px; font-weight: 600; color: #64748B; margin-bottom: 12px;">Tidak Aktif</div>
            <div style="font-size: 26px; font-weight: 700; color: #E11D48; line-height: 1.2;">{{ $stats['unit_tidak_aktif'] }} <span style="font-size: 14px; font-weight: 600; color: #64748B;">Gudang</span></div>
        </div>
    </div>
</div>

<form method="get" class="metric-card mb-4" style="padding: 20px 24px; border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
        <div style="flex: 2 1 300px;">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; margin-bottom: 6px; display: block;">Pencarian Kendaraan</label>
            <input type="text" name="search" class="form-control" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 13px; width: 100%;" placeholder="Cari plat nomor, merk, atau driver..." value="{{ $filters['search'] ?? '' }}">
        </div>
        <div style="flex: 1 1 200px;">
            <label style="font-size: 12px; font-weight: 600; color: #64748B; margin-bottom: 6px; display: block;">Status Operasional</label>
            <select name="status" class="form-select" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 13px; width: 100%;">
                <option value="">— Semua Status —</option>
                @foreach(config('monitoring.vehicle_statuses') as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex: 0 0 140px;">
            <button class="btn w-100" style="background: var(--surface-3); color: var(--text); border: 1px solid var(--border); font-weight: 600; height: 38px; font-family: 'Poppins', sans-serif; font-size: 13px;">Filter Data</button>
        </div>
    </div>
</form>

<div class="metric-card mb-4" style="padding: 0; overflow: hidden; border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="width: 100%; border-collapse: collapse;">
            <thead style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                <tr>
                    <th style="padding: 16px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Plat Nomor</th>
                    <th style="padding: 16px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Spesifikasi</th>
                    <th style="padding: 16px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Driver Pengguna</th>
                    <th style="padding: 16px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                    <th class="text-end" style="padding: 16px 24px; font-size: 11.5px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 16px 24px;">
                        <span style="background: var(--surface-3); color: var(--text); padding: 6px 12px; border-radius: 8px; font-size: 13.5px; font-weight: 700; letter-spacing: 1px;">{{ $vehicle->plat_nomor }}</span>
                    </td>
                    <td style="padding: 16px 24px;">
                        <div style="font-size: 13.5px; font-weight: 600; color: #0F172A;">{{ $vehicle->merk ?? '—' }}</div>
                        <div style="font-size: 12px; color: #64748B;">{{ $vehicle->jenis_kendaraan }} &middot; Tahun {{ $vehicle->tahun ?? '—' }}</div>
                    </td>
                    <td style="padding: 16px 24px; font-size: 13.5px; color: #0F172A; font-weight: 500;">
                        {{ $vehicle->driver_name ?? '—' }}
                    </td>
                    <td style="padding: 16px 24px;">
                        @if($vehicle->status === 'aktif')
                            <span style="background: rgba(16,185,129,.15); color: #10B981; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">{{ config('monitoring.vehicle_statuses')[$vehicle->status] ?? 'Aktif' }}</span>
                        @elseif($vehicle->status === 'servis')
                            <span style="background: rgba(245,158,11,.15); color: #D97706; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">{{ config('monitoring.vehicle_statuses')[$vehicle->status] ?? 'Servis' }}</span>
                        @else
                            <span style="background: rgba(225,29,72,.15); color: #E11D48; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700;">{{ config('monitoring.vehicle_statuses')[$vehicle->status] ?? 'Tidak Aktif' }}</span>
                        @endif
                    </td>
                    <td class="text-end" style="padding: 16px 24px;">
                        <div class="d-flex gap-2 justify-content-end">
                            @can('vehicle.edit')
                            <a class="btn btn-sm" href="{{ route('vehicle.edit', $vehicle) }}" style="background: rgba(59,130,246,.1); color: #3B82F6; border: none; font-weight: 600; padding: 6px 14px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Edit</a>
                            @endcan
                            @can('vehicle.delete')
                            <form class="d-inline m-0" method="post" action="{{ route('vehicle.destroy', $vehicle) }}" onsubmit="return confirm('Hapus kendaraan ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="background: rgba(225,29,72,.1); color: #E11D48; border: none; font-weight: 600; padding: 6px 14px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Hapus</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding: 64px 24px;">
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="width: 56px; height: 56px; background: rgba(59, 130, 246, 0.1); color: #3B82F6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                                <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            </div>
                            <div style="font-size: 16px; font-weight: 700; color: #0F172A; margin-bottom: 4px;">Data Kosong</div>
                            <div style="font-size: 13px; color: #64748B;">Belum ada kendaraan yang terdaftar sesuai pencarian Anda.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 mb-5">
    {{ $vehicles->links() }}
</div>

@endsection
