<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Surat Izin Keluar</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 10.5px; color: #1f2937; }
        h1 { font-size: 16px; margin: 0 0 2px 0; }
        .subtitle { font-size: 10px; color: #6b7280; margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #d1d5db; padding: 5px 6px; text-align: left; }
        th { background: #f1f5f9; font-size: 9.5px; text-transform: uppercase; }
        td { font-size: 10px; }
    </style>
</head>
<body>
    <h1>Laporan Surat Izin Keluar (SIK)</h1>
    <div class="subtitle">
        Dibuat pada {{ now()->format('d M Y H:i') }}
        @if(!empty($filters['date_from']) || !empty($filters['date_to']))
            &middot; Periode: {{ $filters['date_from'] ?? '...' }} s/d {{ $filters['date_to'] ?? '...' }}
        @endif
        @if(!empty($filters['department']))
            &middot; Departemen: {{ $filters['department'] }}
        @endif
        @if(!empty($filters['status']))
            &middot; Status: {{ config('sik.statuses')[$filters['status']] ?? $filters['status'] }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Nomor SIK</th>
                <th>Nama</th>
                <th>Departemen</th>
                <th>Jenis Izin</th>
                <th>Keluar Rencana</th>
                <th>Kembali Rencana</th>
                <th>Keluar Aktual</th>
                <th>Kembali Aktual</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td>{{ $item->nomor_sik ?: '-' }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->department ?: '-' }}</td>
                <td>{{ $item->jenis_izin_label }}</td>
                <td>{{ $item->jam_keluar_rencana->format('d-m-Y H:i') }}</td>
                <td>{{ $item->jam_kembali_rencana->format('d-m-Y H:i') }}</td>
                <td>{{ optional($item->jam_keluar_aktual)->format('d-m-Y H:i') ?: '-' }}</td>
                <td>{{ optional($item->jam_kembali_aktual)->format('d-m-Y H:i') ?: '-' }}</td>
                <td>{{ $item->status_label }}</td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center; padding:16px;">Tidak ada data untuk periode/filter ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
