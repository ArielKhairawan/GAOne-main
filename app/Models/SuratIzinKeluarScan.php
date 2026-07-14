<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratIzinKeluarScan extends GaModel
{
    protected $casts = [
        'scanned_at' => 'datetime',
        'is_success' => 'boolean',
    ];

    public function suratIzinKeluar(): BelongsTo
    {
        return $this->belongsTo(SuratIzinKeluar::class);
    }

    public function security(): BelongsTo
    {
        return $this->belongsTo(User::class, 'security_id');
    }
}
