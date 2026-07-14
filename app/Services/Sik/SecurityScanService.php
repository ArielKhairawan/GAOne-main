<?php

namespace App\Services\Sik;

use App\Models\SuratIzinKeluar;
use App\Models\SuratIzinKeluarScan;
use App\Models\User;
use App\Services\SikNotificationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SecurityScanService
{
    public function __construct(private SikNotificationService $notifier)
    {
    }

    /**
     * Titik masuk tunggal untuk proses scan oleh Security. Token dari QR
     * (UUID) diterjemahkan ke SIK terkait, lalu status SAAT INI menentukan
     * apakah ini scan keluar atau scan kembali — Security tidak memilih
     * jenis scan secara manual, dan status tidak pernah diubah manual.
     */
    public function scan(string $token, User $security): array
    {
        $sik = SuratIzinKeluar::where('uuid', $token)->first();

        if (! $sik) {
            $this->logScan(null, $security, null, false, config('sik.validation_messages.not_found'), $token);

            return $this->result(false, config('sik.validation_messages.not_found'));
        }

        return match ($sik->status) {
            'approved' => $this->scanOut($sik, $security),
            'sedang_keluar' => $this->scanIn($sik, $security),
            'completed' => $this->reject($sik, $security, null, config('sik.validation_messages.completed')),
            'pending_approval' => $this->reject($sik, $security, null, config('sik.validation_messages.pending_approval')),
            'rejected' => $this->reject($sik, $security, null, config('sik.validation_messages.rejected')),
            'cancelled' => $this->reject($sik, $security, null, config('sik.validation_messages.cancelled')),
            default => $this->reject($sik, $security, null, config('sik.validation_messages.not_found')),
        };
    }

    private function scanOut(SuratIzinKeluar $sik, User $security): array
    {
        $sik = DB::transaction(function () use ($sik, $security) {
            $sik->update([
                'jam_keluar_aktual' => now(),
                'security_out_by' => $security->id,
                'security_out_at' => now(),
                'status' => 'sedang_keluar',
            ]);

            $sik = $sik->fresh(['user']);

            $this->logScan($sik, $security, 'keluar', true, 'Scan keluar berhasil.');
            $this->notifier->notifyEmployeeScannedOut($sik);

            return $sik;
        });

        return $this->result(true, 'Scan keluar berhasil dicatat.', $sik, 'keluar');
    }

    private function scanIn(SuratIzinKeluar $sik, User $security): array
    {
        $sik = DB::transaction(function () use ($sik, $security) {
            $sik->update([
                'jam_kembali_aktual' => now(),
                'security_in_by' => $security->id,
                'security_in_at' => now(),
                'status' => 'completed',
            ]);

            $sik = $sik->fresh(['user']);

            $this->logScan($sik, $security, 'kembali', true, 'Scan kembali berhasil.');
            $this->notifier->notifyEmployeeScannedIn($sik);

            return $sik;
        });

        return $this->result(true, 'Scan kembali berhasil dicatat. SIK selesai.', $sik, 'kembali');
    }

    private function reject(SuratIzinKeluar $sik, User $security, ?string $type, string $message): array
    {
        $this->logScan($sik, $security, $type, false, $message);

        return $this->result(false, $message, $sik);
    }

    private function logScan(?SuratIzinKeluar $sik, User $security, ?string $type, bool $success, string $message, ?string $rawToken = null): void
    {
        SuratIzinKeluarScan::create([
            'surat_izin_keluar_id' => $sik?->id,
            'security_id' => $security->id,
            'type' => $type,
            'is_success' => $success,
            'message' => $message,
            'scanned_token' => $sik?->uuid ?: $rawToken,
            'scanned_at' => now(),
        ]);
    }

    private function result(bool $success, string $message, ?SuratIzinKeluar $sik = null, ?string $type = null): array
    {
        return compact('success', 'message', 'sik', 'type');
    }

    public function todayHistoryFor(User $security, int $perPage): LengthAwarePaginator
    {
        return SuratIzinKeluarScan::query()
            ->with(['suratIzinKeluar.user', 'security'])
            ->whereDate('scanned_at', now()->toDateString())
            ->when(
                ! $security->hasAnyRole(['Admin', 'GA Staff']),
                fn ($q) => $q->where('security_id', $security->id)
            )
            ->latest('scanned_at')
            ->paginate($perPage);
    }

    public function todayStats(User $security): array
    {
        $query = SuratIzinKeluarScan::query()->whereDate('scanned_at', now()->toDateString());

        if (! $security->hasAnyRole(['Admin', 'GA Staff'])) {
            $query->where('security_id', $security->id);
        }

        return [
            'total' => (clone $query)->count(),
            'keluar' => (clone $query)->where('type', 'keluar')->where('is_success', true)->count(),
            'kembali' => (clone $query)->where('type', 'kembali')->where('is_success', true)->count(),
            'gagal' => (clone $query)->where('is_success', false)->count(),
        ];
    }
}
