<?php

namespace App\Services;

use App\Models\SuratIzinKeluar;
use App\Models\SystemNotification;
use App\Models\User;

class SikNotificationService
{
    /**
     * Notifikasi ke Manager departemen terkait saat pengajuan SIK dibuat.
     */
    public function notifyManagersOnSubmit(SuratIzinKeluar $sik): void
    {
        $managers = User::role('Manager')
            ->when($sik->department, fn ($q) => $q->where('department', $sik->department))
            ->get();

        foreach ($managers as $manager) {
            SystemNotification::create([
                'user_id' => $manager->id,
                'type' => 'sik.submitted',
                'channel' => 'in-app',
                'title' => 'Pengajuan SIK Baru',
                'body' => "{$sik->user->name} mengajukan Surat Izin Keluar ({$sik->jenis_izin_label}) dan menunggu persetujuan Anda.",
                'sent_at' => now(),
            ]);
        }
    }

    public function notifyEmployeeApproved(SuratIzinKeluar $sik): void
    {
        SystemNotification::create([
            'user_id' => $sik->user_id,
            'type' => 'sik.approved',
            'channel' => 'in-app',
            'title' => 'SIK Disetujui',
            'body' => "Surat Izin Keluar Anda ({$sik->nomor_sik}) telah disetujui oleh {$sik->manager?->name}.",
            'sent_at' => now(),
        ]);
    }

    public function notifyEmployeeRejected(SuratIzinKeluar $sik): void
    {
        SystemNotification::create([
            'user_id' => $sik->user_id,
            'type' => 'sik.rejected',
            'channel' => 'in-app',
            'title' => 'SIK Ditolak',
            'body' => "Surat Izin Keluar Anda ditolak oleh {$sik->manager?->name}.".
                ($sik->approval_note ? " Catatan: {$sik->approval_note}" : ''),
            'sent_at' => now(),
        ]);
    }

    public function notifyEmployeeScannedOut(SuratIzinKeluar $sik): void
    {
        SystemNotification::create([
            'user_id' => $sik->user_id,
            'type' => 'sik.scanned_out',
            'channel' => 'in-app',
            'title' => 'Scan Keluar Berhasil',
            'body' => "Anda tercatat keluar perusahaan pada {$sik->jam_keluar_aktual->format('d M Y H:i')} (SIK {$sik->nomor_sik}).",
            'sent_at' => now(),
        ]);
    }

    public function notifyEmployeeScannedIn(SuratIzinKeluar $sik): void
    {
        SystemNotification::create([
            'user_id' => $sik->user_id,
            'type' => 'sik.scanned_in',
            'channel' => 'in-app',
            'title' => 'Scan Kembali Berhasil',
            'body' => "Anda tercatat kembali ke perusahaan pada {$sik->jam_kembali_aktual->format('d M Y H:i')} (SIK {$sik->nomor_sik}). SIK selesai.",
            'sent_at' => now(),
        ]);
    }
}
