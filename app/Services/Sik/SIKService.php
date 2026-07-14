<?php

namespace App\Services\Sik;

use App\Models\SuratIzinKeluar;
use App\Models\User;
use App\Services\SikNotificationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SIKService
{
    public function __construct(private SikNotificationService $notifier)
    {
    }

    /**
     * Karyawan membuat pengajuan baru. Nama, Nomor Karyawan, dan Departemen
     * TIDAK diinput manual — seluruhnya diambil otomatis dari akun login.
     */
    public function create(array $data, User $user): SuratIzinKeluar
    {
        return DB::transaction(function () use ($data, $user) {
            $data = $this->storeAttachmentIfPresent($data);

            $sik = SuratIzinKeluar::create([
                ...$data,
                'uuid' => (string) Str::uuid(),
                'user_id' => $user->id,
                'department' => $user->department,
                'status' => 'pending_approval',
            ]);

            $this->notifier->notifyManagersOnSubmit($sik->load('user'));

            return $sik;
        });
    }

    public function update(SuratIzinKeluar $sik, array $data): SuratIzinKeluar
    {
        if ($sik->status !== 'pending_approval') {
            throw ValidationException::withMessages([
                'status' => 'Pengajuan hanya dapat diedit selama masih berstatus Pending Approval.',
            ]);
        }

        $data = $this->storeAttachmentIfPresent($data, $sik);

        $sik->update($data);

        return $sik->fresh();
    }

    public function cancel(SuratIzinKeluar $sik): SuratIzinKeluar
    {
        if (! in_array($sik->status, ['pending_approval', 'approved'], true)) {
            throw ValidationException::withMessages([
                'status' => 'Pengajuan yang sudah dalam proses keluar/kembali atau selesai tidak dapat dibatalkan.',
            ]);
        }

        $sik->update(['status' => 'cancelled']);

        return $sik->fresh();
    }

    public function findByUuidOrFail(string $uuid): SuratIzinKeluar
    {
        return SuratIzinKeluar::where('uuid', $uuid)->firstOrFail();
    }

    public function list(User $user, array $filters, int $perPage): LengthAwarePaginator
    {
        $query = SuratIzinKeluar::query()->with(['user', 'manager', 'securityOut', 'securityIn'])
            ->latest('created_at');

        // Karyawan (tanpa akses admin/manager) hanya melihat riwayat miliknya sendiri.
        if (! $user->hasAnyRole(['Admin', 'Manager', 'GA Staff', 'Security'])) {
            $query->where('user_id', $user->id);
        }

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage)->withQueryString();
    }

    public function forExport(array $filters, ?User $scopeToUser = null)
    {
        $query = SuratIzinKeluar::query()->with(['user', 'manager', 'securityOut', 'securityIn']);

        if ($scopeToUser) {
            $query->where('user_id', $scopeToUser->id);
        }

        $this->applyFilters($query, $filters);

        return $query->latest('created_at')->get();
    }

    public function dashboardStats(User $user): array
    {
        $query = SuratIzinKeluar::query();

        if (! $user->hasAnyRole(['Admin', 'Manager', 'GA Staff'])) {
            $query->where('user_id', $user->id);
        }

        $base = (clone $query);

        return [
            'total' => (clone $base)->count(),
            'pending_approval' => (clone $base)->where('status', 'pending_approval')->count(),
            'approved' => (clone $base)->where('status', 'approved')->count(),
            'sedang_keluar' => (clone $base)->where('status', 'sedang_keluar')->count(),
            'completed' => (clone $base)->where('status', 'completed')->count(),
            'rejected' => (clone $base)->where('status', 'rejected')->count(),
        ];
    }

    public function monthlyChart(User $user): array
    {
        $query = SuratIzinKeluar::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth());

        if (! $user->hasAnyRole(['Admin', 'Manager', 'GA Staff'])) {
            $query->where('user_id', $user->id);
        }

        return $query->groupBy('bulan')->orderBy('bulan')->pluck('total', 'bulan')->all();
    }

    public function statusChart(User $user): array
    {
        return $this->dashboardStats($user);
    }

    private function applyFilters($query, array $filters): void
    {
        $query
            ->when($filters['date_from'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($filters['date_to'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->when($filters['department'] ?? null, fn ($q, $v) => $q->where('department', $v))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['jenis_izin'] ?? null, fn ($q, $v) => $q->where('jenis_izin', $v))
            ->when($filters['employee_id'] ?? null, fn ($q, $v) => $q->where('user_id', $v));
    }

    private function storeAttachmentIfPresent(array $data, ?SuratIzinKeluar $existing = null): array
    {
        if (! isset($data['lampiran']) || ! $data['lampiran'] instanceof UploadedFile) {
            unset($data['lampiran']);

            return $data;
        }

        if ($existing && $existing->lampiran) {
            Storage::disk('public')->delete($existing->lampiran);
        }

        $data['lampiran'] = $data['lampiran']->store('sik-lampiran', 'public');

        return $data;
    }
}
