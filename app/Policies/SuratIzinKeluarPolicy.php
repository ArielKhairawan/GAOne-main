<?php

namespace App\Policies;

use App\Models\SuratIzinKeluar;
use App\Models\User;

class SuratIzinKeluarPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('sik.view');
    }

    public function view(User $user, SuratIzinKeluar $sik): bool
    {
        if (! $user->can('sik.view')) {
            return false;
        }

        // Pemilik pengajuan selalu boleh melihat miliknya sendiri.
        if ($sik->user_id === $user->id) {
            return true;
        }

        // Manager hanya boleh melihat pengajuan dari departemennya sendiri.
        if ($user->hasRole('Manager') && ! $user->hasAnyRole(['Admin', 'GA Staff'])) {
            return $sik->department === $user->department;
        }

        // Admin, GA Staff, dan Security (untuk keperluan scan) boleh melihat seluruh data.
        return $user->hasAnyRole(['Admin', 'GA Staff', 'Security']);
    }

    public function create(User $user): bool
    {
        return $user->can('sik.create');
    }

    public function update(User $user, SuratIzinKeluar $sik): bool
    {
        if (! $user->can('sik.edit')) {
            return false;
        }

        // Karyawan hanya dapat mengedit/membatalkan pengajuannya sendiri.
        if (! $user->hasAnyRole(['Admin', 'GA Staff'])) {
            return $sik->user_id === $user->id;
        }

        return true;
    }

    public function delete(User $user, SuratIzinKeluar $sik): bool
    {
        return $user->can('sik.delete') && $user->hasAnyRole(['Admin', 'GA Staff']);
    }

    public function approve(User $user, SuratIzinKeluar $sik): bool
    {
        if (! $user->can('sik.approve')) {
            return false;
        }

        if ($user->hasAnyRole(['Admin', 'GA Staff'])) {
            return true;
        }

        return $user->hasRole('Manager') && $sik->department === $user->department;
    }

    public function scan(User $user): bool
    {
        return $user->can('sik.scan');
    }
}
