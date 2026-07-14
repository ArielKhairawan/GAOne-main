@extends('layouts.app')

@section('title', 'Detail Inspeksi WC')
@section('page-title', 'Detail Inspeksi WC')
@section('page-subtitle', 'Laporan: ' . ($inspection->lokasi_detail ?: $inspection->lokasi))

@section('content')

<!-- =========================================================
     HEADER (TOMBOL KEMBALI & EDIT)
     ========================================================= -->
<div class="d-flex justify-content-end align-items-center mb-4 gap-2">
    <a class="btn btn-sm" href="{{ route('toilet.index') }}" style="background: #ffffff; border: 1px solid #E2E8F0; color: #475569; font-weight: 600; padding: 8px 16px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Kembali</a>
    @can('toilet.edit')
    <a class="btn btn-sm" href="{{ route('toilet.edit', $inspection) }}" style="background: rgba(59,130,246,.1); color: #3B82F6; border: none; font-weight: 600; padding: 8px 16px; border-radius: 8px; font-family: 'Poppins', sans-serif;">Edit Data</a>
    @endcan
</div>

<!-- =========================================================
     RINGKASAN UTAMA (FLEXBOX)
     ========================================================= -->
<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden; padding: 24px;">
    <div style="display: flex; flex-wrap: wrap; gap: 32px; align-items: center;">

        <div style="flex: 1 1 200px;">
            <div style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Lokasi</div>
            <div style="font-size: 18px; font-weight: 700; color: #0F172A;">{{ $inspection->lokasi_detail ?: $inspection->lokasi }}</div>
        </div>

        <div style="flex: 1 1 150px;">
            <div style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Waktu Inspeksi</div>
            <div style="font-size: 15px; font-weight: 600; color: #0F172A;">{{ $inspection->tanggal->format('d M Y') }} &middot; {{ $inspection->jam }}</div>
        </div>

        <div style="flex: 1 1 150px;">
            <div style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Petugas</div>
            <div style="font-size: 15px; font-weight: 600; color: #0F172A;">{{ $inspection->petugas_name }}</div>
        </div>

        <div style="flex: 0 0 auto;">
            <div style="font-size: 12px; font-weight: 600; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">Status Akhir</div>
            @if($inspection->status === 'bersih')
                <div style="background: rgba(16,185,129,.15); color: #10B981; padding: 6px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; text-align: center;">{{ config('monitoring.toilet_statuses')[$inspection->status] ?? $inspection->status }}</div>
            @else
                <div style="background: rgba(225,29,72,.15); color: #E11D48; padding: 6px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; text-align: center;">{{ config('monitoring.toilet_statuses')[$inspection->status] ?? $inspection->status }}</div>
            @endif
        </div>

    </div>
</div>

<!-- =========================================================
     KONTEN DIBELAH DUA (FLEXBOX)
     ========================================================= -->
<div style="display: flex; flex-wrap: wrap; gap: 24px;">

    <!-- KOLOM KIRI: CHECKLIST & CATATAN -->
    <div style="flex: 2 1 500px; min-width: 0;">
        <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
                <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Rincian Checklist Kebersihan</div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                        @foreach($inspection->items as $item)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 16px 24px; font-size: 14px; font-weight: 600; color: #475569;">
                                {{ $item->item_name }}
                            </td>
                            <td class="text-end" style="padding: 16px 24px;">
                                @if($item->status === 'baik')
                                    <span style="background: rgba(16,185,129,.1); color: #10B981; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700;">Baik / Bersih</span>
                                @elseif($item->status === 'kurang')
                                    <span style="background: rgba(245,158,11,.1); color: #D97706; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700;">Kurang Bersih</span>
                                @else
                                    <span style="background: rgba(225,29,72,.1); color: #E11D48; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 700;">Rusak / Bermasalah</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($inspection->catatan)
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
                <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Catatan Temuan Petugas</div>
            </div>
            <div style="padding: 24px; font-size: 14px; color: #475569; line-height: 1.6;">
                {{ $inspection->catatan }}
            </div>
        </div>
        @endif
    </div>

    <!-- KOLOM KANAN: FOTO & TANDA TANGAN -->
    <div style="flex: 1 1 300px; min-width: 0;">
        @if($inspection->foto)
        <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
                <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Bukti Foto</div>
            </div>
            <div style="padding: 24px; display: flex; justify-content: center;">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($inspection->foto) }}" alt="Foto inspeksi" style="width: 100%; max-width: 400px; border-radius: 12px; border: 1px solid #E2E8F0; object-fit: cover;">
            </div>
        </div>
        @endif

        @if($inspection->tanda_tangan)
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid var(--border); background: #F8FAFC;">
                <div style="font-size: 15px; font-weight: 700; color: #0F172A;">Tanda Tangan Petugas</div>
            </div>
            <div style="padding: 24px; display: flex; justify-content: center; align-items: center; background: #F8FAFC; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                <img src="{{ $inspection->tanda_tangan }}" alt="Tanda tangan" style="width: 100%; max-width: 300px; object-fit: contain;">
            </div>
        </div>
        @endif
    </div>

</div>

@endsection
