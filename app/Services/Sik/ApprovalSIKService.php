<?php

namespace App\Services\Sik;

use App\Models\SuratIzinKeluar;
use App\Models\User;
use App\Services\SikNotificationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApprovalSIKService
{
    public function __construct(private SikNotificationService $notifier)
    {
    }

    /**
     * Manager hanya dapat melihat pengajuan dari bawahannya, yaitu
     * karyawan pada departemen yang sama dengan Manager tersebut. Admin
     * dan GA Staff dapat melihat seluruh pengajuan untuk keperluan
     * pemantauan/administrasi.
     */
    public function queueFor(User $manager, array $filters, int $perPage): LengthAwarePaginator
    {
        $query = SuratIzinKeluar::query()
            ->with(['user', 'manager'])
            ->where('status', 'pending_approval')
            ->latest('created_at');

        if ($manager->hasRole('Manager') && ! $manager->hasAnyRole(['Admin', 'GA Staff'])) {
            $query->where('department', $manager->department);
        }

        if (! empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function approve(SuratIzinKeluar $sik, User $manager, ?string $note = null): SuratIzinKeluar
    {
        $this->guardManagerScope($sik, $manager);

        if ($sik->status !== 'pending_approval') {
            throw ValidationException::withMessages([
                'status' => 'Pengajuan ini sudah diproses sebelumnya.',
            ]);
        }

        return DB::transaction(function () use ($sik, $manager, $note) {
            $sik->update([
                'status' => 'approved',
                'approved_by' => $manager->id,
                'approved_at' => now(),
                'approval_note' => $note,
                'nomor_sik' => $this->generateNomorSik(),
            ]);

            $sik = $sik->fresh(['user', 'manager']);

            $this->notifier->notifyEmployeeApproved($sik);

            return $sik;
        });
    }

    public function reject(SuratIzinKeluar $sik, User $manager, ?string $note = null): SuratIzinKeluar
    {
        $this->guardManagerScope($sik, $manager);

        if ($sik->status !== 'pending_approval') {
            throw ValidationException::withMessages([
                'status' => 'Pengajuan ini sudah diproses sebelumnya.',
            ]);
        }

        return DB::transaction(function () use ($sik, $manager, $note) {
            $sik->update([
                'status' => 'rejected',
                'approved_by' => $manager->id,
                'approved_at' => now(),
                'approval_note' => $note,
            ]);

            $sik = $sik->fresh(['user', 'manager']);

            $this->notifier->notifyEmployeeRejected($sik);

            return $sik;
        });
    }

    private function guardManagerScope(SuratIzinKeluar $sik, User $manager): void
    {
        if ($manager->hasAnyRole(['Admin', 'GA Staff'])) {
            return;
        }

        if ($sik->department !== $manager->department) {
            throw ValidationException::withMessages([
                'department' => 'Anda hanya dapat memproses pengajuan dari departemen Anda sendiri.',
            ]);
        }
    }

    /**
     * Nomor SIK dibuat otomatis HANYA setelah pengajuan disetujui, dengan
     * format SIK-{tahun}-{urutan 6 digit}. Dibungkus dalam transaction dan
     * memakai lockForUpdate supaya tetap unik meski di-approve bersamaan
     * (race condition) oleh lebih dari satu proses.
     */
    private function generateNomorSik(): string
    {
        $year = now()->year;

        $lastNumber = SuratIzinKeluar::where('nomor_sik', 'like', "SIK-{$year}-%")
            ->lockForUpdate()
            ->orderByDesc('nomor_sik')
            ->value('nomor_sik');

        $nextSequence = 1;

        if ($lastNumber) {
            $parts = explode('-', $lastNumber);
            $nextSequence = ((int) end($parts)) + 1;
        }

        $nomor = sprintf('SIK-%d-%06d', $year, $nextSequence);

        // Jaga-jaga terhadap tabrakan nomor (mis. data lama / concurrent insert).
        while (SuratIzinKeluar::where('nomor_sik', $nomor)->exists()) {
            $nextSequence++;
            $nomor = sprintf('SIK-%d-%06d', $year, $nextSequence);
        }

        return $nomor;
    }
}
