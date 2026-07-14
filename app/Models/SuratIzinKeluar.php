<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class SuratIzinKeluar extends GaModel
{
    protected $casts = [
        'jam_keluar_rencana' => 'datetime',
        'jam_kembali_rencana' => 'datetime',
        'jam_keluar_aktual' => 'datetime',
        'jam_kembali_aktual' => 'datetime',
        'approved_at' => 'datetime',
        'security_out_at' => 'datetime',
        'security_in_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias deskriptif sesuai istilah pada spesifikasi modul ("belongsTo
     * Department"). Karena project ini tidak memiliki tabel departments
     * terpisah, departemen disimpan sebagai snapshot string pada kolom
     * `department` (lihat migration). Accessor ini disediakan agar Blade
     * dapat memakai `$sik->departmentName` secara konsisten.
     */
    public function getDepartmentNameAttribute(): ?string
    {
        return $this->department;
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function securityOut(): BelongsTo
    {
        return $this->belongsTo(User::class, 'security_out_by');
    }

    public function securityIn(): BelongsTo
    {
        return $this->belongsTo(User::class, 'security_in_by');
    }

    public function scans(): HasMany
    {
        return $this->hasMany(SuratIzinKeluarScan::class)->latest('scanned_at');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        return config("sik.statuses.{$this->status}", $this->status);
    }

    public function getStatusBadgeAttribute(): string
    {
        return config("sik.status_badges.{$this->status}", 'secondary');
    }

    public function getJenisIzinLabelAttribute(): string
    {
        return config("sik.jenis_izin.{$this->jenis_izin}", $this->jenis_izin);
    }

    public function getDurasiDiLuarAttribute(): ?string
    {
        if (! $this->jam_keluar_aktual) {
            return null;
        }

        $end = $this->jam_kembali_aktual ?: Carbon::now();
        $diff = $this->jam_keluar_aktual->diff($end);

        return trim(
            ($diff->h > 0 ? $diff->h.' jam ' : '').
            $diff->i.' menit'
        );
    }

    public function isReadOnly(): bool
    {
        return in_array($this->status, ['completed', 'rejected', 'cancelled'], true);
    }
}
