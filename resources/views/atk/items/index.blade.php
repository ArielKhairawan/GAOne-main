@extends('layouts.app')

@section('title', 'Data ATK')
@section('page-title', 'Inventaris ATK')
@section('page-subtitle', 'Total ' . $items->total() . ' jenis barang terdaftar dalam sistem.')

@section('content')

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 mt-2">
    <div>
        @if($lowStockCount > 0)
            <div style="background: rgba(225,29,72,.08); color: #E11D48; padding: 6px 16px; border-radius: 30px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
                <span style="width: 6px; height: 6px; background-color: #E11D48; border-radius: 50%; display: inline-block;"></span>
                {{ $lowStockCount }} item perlu perhatian (stok menipis/habis)
            </div>
        @else
            <div style="background: rgba(16,185,129,.08); color: #10B981; padding: 6px 16px; border-radius: 30px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; font-family: 'Poppins', sans-serif;">
                <span style="width: 6px; height: 6px; background-color: #10B981; border-radius: 50%; display: inline-block;"></span>
                Semua stok aman
            </div>
        @endif
    </div>

    <div class="d-flex flex-wrap gap-2">
        @can('atk.export')
        <a class="btn d-inline-flex align-items-center justify-content-center hover-scale" href="{{ route('atk.items.export.pdf') }}"
           style="background-color: #fbebee; color: #d60a2b; border: none; font-weight: 600; border-radius: 12px; padding: 8px 20px; font-size: 13.5px; font-family: 'Poppins', sans-serif; transition: all 0.2s;">
            Unduh PDF
        </a>
        <a class="btn d-inline-flex align-items-center justify-content-center hover-scale" href="{{ route('atk.items.export.excel') }}"
           style="background-color: #e6f4ea; color: #137333; border: none; font-weight: 600; border-radius: 12px; padding: 8px 20px; font-size: 13.5px; font-family: 'Poppins', sans-serif; transition: all 0.2s;">
            Unduh Excel
        </a>
        @endcan

        @can('atk.create')
        <a class="btn d-inline-flex align-items-center justify-content-center hover-scale" href="{{ route('atk.items.create') }}"
           style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; border-radius: 12px; padding: 8px 20px; font-size: 13.5px; font-family: 'Poppins', sans-serif; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah Data
        </a>
        @endcan
    </div>
</div>

<form method="get" class="metric-card mb-4" style="padding: 24px; border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    <div style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
        <div style="flex: 2 1 300px;">
            <label style="font-size: 12.5px; font-weight: 600; color: #64748B; margin-bottom: 8px; display: block;">Pencarian Barang</label>
            <input type="text" name="search" class="form-control" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 13.5px; height: 42px; width: 100%; border-radius: 10px;" placeholder="Cari nama atau kode barang..." value="{{ request('search', $filters['search'] ?? '') }}">
        </div>
        <div style="flex: 1 1 200px;">
            <label style="font-size: 12.5px; font-weight: 600; color: #64748B; margin-bottom: 8px; display: block;">Kategori ATK</label>
            <select name="atk_category_id" class="form-select" style="background: #F8FAFC; border: 1px solid #E2E8F0; font-family: 'Poppins', sans-serif; font-size: 13.5px; height: 42px; width: 100%; border-radius: 10px;">
                <option value="">— Semua Kategori —</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(request('atk_category_id', $filters['atk_category_id'] ?? '') == $cat->id)>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div style="flex: 0 0 140px;">
            <button class="btn w-100" style="background: #F1F5F9; color: #0F172A; border: 1px solid #E2E8F0; font-weight: 600; height: 42px; font-family: 'Poppins', sans-serif; font-size: 13.5px; border-radius: 10px; transition: all 0.2s;">Filter Data</button>
        </div>
    </div>
</form>

<div class="metric-card mb-4" style="padding: 0; overflow: hidden; border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="width: 100%; border-collapse: collapse;">
            <thead style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                <tr>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; width: 12%;">Kode</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; width: 23%;">Nama Barang</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; width: 15%;">Kategori</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; width: 10%;">Satuan</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; width: 15%;">Stok / Min</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; width: 15%;">Lokasi</th>
                    <th style="padding: 16px 20px; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; width: 10%;">Status</th>
                    <th class="text-end" style="padding: 16px 20px; font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 16px 20px; white-space: nowrap;">
                        <span style="background: #F1F5F9; color: #475569; padding: 6px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; letter-spacing: 0.3px; font-family: monospace;">
                            {{ $item->code }}
                        </span>
                    </td>
                    <td style="padding: 16px 20px;">
                        <div style="font-size: 13.5px; font-weight: 600; color: #0F172A; white-space: normal; line-height: 1.4;">{{ $item->name }}</div>
                    </td>
                    <td style="padding: 16px 20px; font-size: 13px; color: #64748B; font-weight: 500;">
                        {{ $item->category->name ?? '—' }}
                    </td>
                    <td style="padding: 16px 20px; font-size: 13px; color: #64748B;">
                        {{ $item->satuan }}
                    </td>
                    <td style="padding: 16px 20px; white-space: nowrap;">
                        <div style="font-size: 13.5px; font-weight: 600; color: #0F172A;">
                            <span style="font-weight: 700; color: {{ $item->stock <= $item->minimum_stock ? '#E11D48' : '#0F172A' }};">{{ $item->stock }}</span>
                            <span style="font-size: 11.5px; color: #94A3B8; font-weight: 400;">/ Min: {{ $item->minimum_stock }}</span>
                        </div>
                    </td>
                    <td style="padding: 16px 20px; font-size: 13px; color: #475569; font-weight: 500; white-space: normal; line-height: 1.4;">
                        {{ $item->lokasi_penyimpanan ?? '—' }}
                    </td>
                    <td style="padding: 16px 20px; white-space: nowrap;">
                        @if($item->status === 'tersedia')
                            <span style="background: rgba(16,185,129,.1); color: #10B981; padding: 5px 12px; border-radius: 30px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                <span style="font-size: 8px;">●</span> {{ config('monitoring.atk_item_statuses')[$item->status] ?? 'Tersedia' }}
                            </span>
                        @elseif($item->status === 'habis' || $item->stock == 0)
                            <span style="background: rgba(225,29,72,.1); color: #E11D48; padding: 5px 12px; border-radius: 30px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                <span style="font-size: 8px;">●</span> Habis
                            </span>
                        @else
                            <span style="background: rgba(245,158,11,.1); color: #D97706; padding: 5px 12px; border-radius: 30px; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                <span style="font-size: 8px;">●</span> Stok Menipis
                            </span>
                        @endif
                    </td>
                    <td class="text-end" style="padding: 16px 20px;">
                        <div class="d-flex gap-1 justify-content-end align-items-center">
                            @can('atk.edit')
                            <a class="btn btn-sm hover-scale-btn" href="{{ route('atk.items.edit', $item) }}"
                               style="background: rgba(59,130,246,.08); color: #3B82F6; border: none; font-weight: 600; padding: 5px 12px; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 12px; transition: all 0.15s; margin-right: 4px;">
                                Edit
                            </a>
                            @endcan
                            @can('atk.delete')
                            <form class="d-inline m-0" method="post" action="{{ route('atk.items.destroy', $item) }}" onsubmit="return confirm('Hapus barang ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm hover-scale-btn" style="background: rgba(225,29,72,.08); color: #E11D48; border: none; font-weight: 600; padding: 5px 12px; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 12px; transition: all 0.15s;">
                                    Hapus
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding: 48px 24px;">
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <div style="font-size: 14px; font-weight: 600; color: #64748B; font-family: 'Poppins', sans-serif;">Data barang tidak ditemukan.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 mb-5">
    {{ $items->appends(request()->query())->links() }}
</div>

<style>
    .hover-scale:hover {
        transform: translateY(-1px);
        filter: brightness(0.97);
    }
    .hover-scale-btn:hover {
        transform: scale(1.03);
        filter: brightness(0.95);
    }
</style>

@endsection
