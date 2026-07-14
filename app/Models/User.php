<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Auditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use \OwenIt\Auditing\Auditable;
    use HasFactory, HasRoles, LogsActivity, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'employee_number',
        'email',
        'phone',
        'department',
        'position',
        'photo_path',
        'google_id',
        'is_active',
        'last_login_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function loginActivities(): HasMany
    {
        return $this->hasMany(LoginActivity::class);
    }

    public function suratIzinKeluars(): HasMany
    {
        return $this->hasMany(\App\Models\SuratIzinKeluar::class);
    }

    /**
     * Nomor karyawan untuk keperluan modul SIK. Jika belum diisi secara
     * eksplisit (kolom lama, sebelum modul SIK ada), fallback ke ID user
     * yang diformat agar tetap ada nilai yang bisa ditampilkan/dicetak.
     */
    public function getEmployeeNumberDisplayAttribute(): string
    {
        return $this->employee_number ?: 'EMP-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }
}
