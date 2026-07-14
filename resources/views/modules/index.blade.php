@extends('layouts.app')

@section('title', $meta['title'])

@section('content')

<!-- Header Form (Tombol Tambah di Sebelah Kanan) -->
<div class="d-flex justify-content-end align-items-center mb-4" style="font-family: 'Poppins', sans-serif;">
    <a class="btn btn-sm" href="{{ route('modules.create', $meta['slug']) }}" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15); transition: all 0.2s;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Tambah Data
    </a>
</div>

<!-- Informasi Jumlah Data -->
<div class="mb-4" style="font-family: 'Poppins', sans-serif;">
    <span style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Statistik Entri</span>
    <h4 style="font-size: 18px; font-weight: 700; color: #0F172A; margin: 2px 0 0 0;">Total {{ $records->total() }} Data Tersedia</h4>
</div>

<!-- Tabel Informasi Dinamis -->
<div class="card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); font-family: 'Poppins', sans-serif; overflow: hidden;">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-size: 13px;">
            <thead style="background: #F8FAFC;">
                <tr>
                    <th style="padding: 16px 20px; font-weight: 600; color: #475569; border-bottom: 1px solid #E2E8F0;">#ID</th>
                    @foreach(array_keys($meta['fields']) as $field)
                        <th style="padding: 16px 20px; font-weight: 600; color: #475569; border-bottom: 1px solid #E2E8F0;">{{ str($field)->headline() }}</th>
                    @endforeach
                    <th style="padding: 16px 20px; font-weight: 600; color: #475569; border-bottom: 1px solid #E2E8F0;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.15s;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 16px 20px; font-size:12.5px; color:#64748B; font-variant-numeric:tabular-nums; font-weight:600">
                        #{{ str_pad($record->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    @foreach(array_keys($meta['fields']) as $field)
                    <td style="padding: 16px 20px;">
                        @if($field === 'status')
                            @php $s = data_get($record, $field); @endphp
                            @if(in_array($s, ['approved','completed']))
                                <span class="badge" style="background: #D1FAE5; color: #065F46; font-weight: 600; font-size: 11px; padding: 6px 10px; border-radius: 6px;">{{ ucfirst($s) }}</span>
                            @elseif(in_array($s, ['rejected']))
                                <span class="badge" style="background: #FDE8E8; color: #9B1C1C; font-weight: 600; font-size: 11px; padding: 6px 10px; border-radius: 6px;">{{ ucfirst($s) }}</span>
                            @else
                                <span class="badge" style="background: #FEF3C7; color: #92400E; font-weight: 600; font-size: 11px; padding: 6px 10px; border-radius: 6px;">{{ ucfirst($s ?? '—') }}</span>
                            @endif
                        @elseif(in_array($field, ['departure_date','return_date','po_date','starts_at','ends_at','sent_at','completed_at','realized_at']) || str_ends_with($field, '_at') || str_ends_with($field, '_date'))
                            <span style="color:#64748B; font-size:13px">
                                {{ optional(is_string(data_get($record, $field)) ? \Carbon\Carbon::parse(data_get($record, $field)) : data_get($record, $field))?->format('d M Y') ?? '—' }}
                            </span>
                        @elseif(in_array($field, ['estimated_cost','total_amount','ticket_cost','hotel_cost','transport_cost','daily_allowance','other_cost']) || str_contains($field, '_cost') || str_contains($field, '_amount'))
                            <span style="font-variant-numeric:tabular-nums; font-weight: 500; color: #0F172A;">
                                Rp {{ number_format((float) data_get($record, $field), 0, ',', '.') }}
                            </span>
                        @elseif(str_starts_with($field, 'is_'))
                            @if(data_get($record, $field))
                                <span class="badge" style="background: #D1FAE5; color: #065F46; font-weight: 600; font-size: 11px; padding: 6px 10px; border-radius: 6px;">Aktif</span>
                            @else
                                <span class="badge" style="background: #F1F5F9; color: #475569; font-weight: 600; font-size: 11px; padding: 6px 10px; border-radius: 6px;">Nonaktif</span>
                            @endif
                        @else
                            <span style="color:#334155; font-weight: 500;">{{ data_get($record, $field) ?? '—' }}</span>
                        @endif
                    </td>
                    @endforeach
                    <td style="padding: 16px 20px;" class="text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <a class="btn btn-sm" href="{{ route('modules.edit', [$meta['slug'], $record->id]) }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 6px 12px; border-radius: 6px; font-size: 12px; transition: all 0.15s;">
                                Edit
                            </a>

                            @if(in_array($record->status ?? null, ['draft', 'revision'], true))
                            <form class="d-inline" method="post" action="{{ route('modules.submit', [$meta['slug'], $record->id]) }}">
                                @csrf
                                <button class="btn btn-sm" style="background: #DEF7EC; color: #03543F; border: 1px solid #BCF0DA; font-weight: 600; padding: 6px 12px; border-radius: 6px; font-size: 12px;">Submit</button>
                            </form>
                            @endif

                            <form class="d-inline" method="post" action="{{ route('modules.destroy', [$meta['slug'], $record->id]) }}" onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm" style="background: #FDE8E8; color: #9B1C1C; border: 1px solid #FBD5D5; font-weight: 600; padding: 6px 12px; border-radius: 6px; font-size: 12px;">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($meta['fields']) + 2 }}" style="text-align:center; padding: 56px 20px; color: #94A3B8; font-size: 14px;">
                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" style="color: #CBD5E1; margin-bottom: 12px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        <div>Belum ada data. Klik <strong>Tambah Data</strong> untuk membuat entri pertama.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination Grid -->
<div class="mt-4 d-flex justify-content-center" style="font-family: 'Poppins', sans-serif;">
    {{ $records->links() }}
</div>

@endsection
