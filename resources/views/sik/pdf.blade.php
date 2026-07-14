<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Izin Keluar</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; font-size: 12px; color: #1f2937; }
        .header { text-align: center; border-bottom: 2px solid #203A63; padding-bottom: 10px; margin-bottom: 16px; }
        .header h1 { font-size: 18px; margin: 0 0 4px 0; color: #203A63; }
        .header .subtitle { font-size: 11px; color: #6b7280; }
        .nomor { text-align: center; font-size: 13px; font-weight: bold; margin-bottom: 18px; }
        table.info { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table.info td { padding: 4px 6px; vertical-align: top; font-size: 11.5px; }
        table.info td.label { width: 160px; color: #6b7280; }
        table.info td.sep { width: 10px; }
        .box { border: 1px solid #d1d5db; border-radius: 6px; padding: 12px 14px; margin-bottom: 16px; }
        .box h3 { font-size: 12px; margin: 0 0 8px 0; text-transform: uppercase; color: #203A63; }
        .status { display: inline-block; padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: bold; }
        .qr-wrap { text-align: center; margin: 18px 0; }
        .qr-wrap img { width: 140px; height: 140px; }
        .signatures { width: 100%; margin-top: 30px; }
        .signatures td { width: 33%; text-align: center; font-size: 11px; padding-top: 50px; }
        .signatures .name { border-top: 1px solid #1f2937; padding-top: 4px; display: inline-block; min-width: 140px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SURAT IZIN KELUAR (SIK)</h1>
        <div class="subtitle">Sistem Monitoring Operasional Terintegrasi</div>
    </div>

    <div class="nomor">Nomor: {{ $sik->nomor_sik ?: '(belum terbit)' }}</div>

    <table class="info">
        <tr>
            <td class="label">Nama</td><td class="sep">:</td><td>{{ $sik->user->name }}</td>
            <td class="label">Jenis Izin</td><td class="sep">:</td><td>{{ $sik->jenis_izin_label }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Karyawan</td><td class="sep">:</td><td>{{ $sik->user->employee_number_display }}</td>
            <td class="label">Kendaraan</td><td class="sep">:</td><td>{{ $sik->kendaraan ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Departemen</td><td class="sep">:</td><td>{{ $sik->department ?: '-' }}</td>
            <td class="label">Status</td><td class="sep">:</td><td>{{ $sik->status_label }}</td>
        </tr>
    </table>

    <div class="box">
        <h3>Keperluan</h3>
        <div>{{ $sik->keperluan }}</div>
        @if($sik->catatan)
            <div style="margin-top:6px; color:#6b7280">Catatan: {{ $sik->catatan }}</div>
        @endif
    </div>

    <table class="info">
        <tr>
            <td class="label">Jam Keluar Rencana</td><td class="sep">:</td><td>{{ $sik->jam_keluar_rencana->format('d-m-Y H:i') }}</td>
            <td class="label">Jam Kembali Rencana</td><td class="sep">:</td><td>{{ $sik->jam_kembali_rencana->format('d-m-Y H:i') }}</td>
        </tr>
        @if($sik->jam_keluar_aktual)
        <tr>
            <td class="label">Jam Keluar Aktual</td><td class="sep">:</td><td>{{ $sik->jam_keluar_aktual->format('d-m-Y H:i') }}</td>
            <td class="label">Jam Masuk Aktual</td><td class="sep">:</td><td>{{ $sik->jam_kembali_aktual?->format('d-m-Y H:i') ?? '-' }}</td>
        </tr>
        @endif
    </table>

    @if($qrImage)
    <div class="qr-wrap">
        <img src="{{ $qrImage }}" alt="QR Code">
        <div style="font-size:10px; color:#6b7280; margin-top:4px">Pindai QR ini di pos Security saat keluar &amp; kembali</div>
    </div>
    @endif

    <table class="signatures">
        <tr>
            <td>
                <div>Pemohon</div>
                <div class="name">{{ $sik->user->name }}</div>
            </td>
            <td>
                <div>Menyetujui</div>
                <div class="name">{{ $sik->manager?->name ?: '-' }}</div>
            </td>
            <td>
                <div>Security</div>
                <div class="name">{{ $sik->securityOut?->name ?: '-' }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
