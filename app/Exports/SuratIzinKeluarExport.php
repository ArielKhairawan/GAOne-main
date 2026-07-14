<?php

namespace App\Exports;

use App\Models\SuratIzinKeluar;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SuratIzinKeluarExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $items)
    {
    }

    public function collection(): Collection
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'Nomor SIK',
            'Nama',
            'Nomor Karyawan',
            'Departemen',
            'Jenis Izin',
            'Keperluan',
            'Kendaraan',
            'Jam Keluar Rencana',
            'Jam Kembali Rencana',
            'Jam Keluar Aktual',
            'Jam Kembali Aktual',
            'Status',
            'Disetujui Oleh',
            'Tanggal Approval',
            'Catatan Approval',
            'Security Keluar',
            'Security Masuk',
        ];
    }

    public function map($sik): array
    {
        /** @var SuratIzinKeluar $sik */
        return [
            $sik->nomor_sik ?: '-',
            $sik->user->name,
            $sik->user->employee_number_display,
            $sik->department,
            $sik->jenis_izin_label,
            $sik->keperluan,
            $sik->kendaraan,
            optional($sik->jam_keluar_rencana)->format('d-m-Y H:i'),
            optional($sik->jam_kembali_rencana)->format('d-m-Y H:i'),
            optional($sik->jam_keluar_aktual)->format('d-m-Y H:i'),
            optional($sik->jam_kembali_aktual)->format('d-m-Y H:i'),
            $sik->status_label,
            optional($sik->manager)->name,
            optional($sik->approved_at)->format('d-m-Y H:i'),
            $sik->approval_note,
            optional($sik->securityOut)->name,
            optional($sik->securityIn)->name,
        ];
    }
}
