<?php

namespace App\Policies;

use App\Models\LaporanKegiatan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LaporanKegiatanPolicy
{
    /**
     * Determine whether user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Both admin and supervisor can view list
        return in_array($user->role->role_name, ['admin', 'supervisor']);
    }

    /**
     * Determine whether user can view model.
     */
    public function view(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Both admin and supervisor can view details
        return in_array($user->role->role_name, ['admin', 'supervisor']);
    }

    /**
     * Determine whether user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin can create laporan
        return $user->role->role_name === 'admin';
    }

    /**
     * Determine whether user can update model.
     */
    public function update(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Only admin can update laporan
        return $user->role->role_name === 'admin';
    }

    /**
     * Determine whether user can delete model.
     */
    public function delete(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Only admin can delete laporan
        return $user->role->role_name === 'admin';
    }

    /**
     * Determine whether user can restore model.
     */
    public function restore(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Only admin can restore laporan
        return $user->role->role_name === 'admin';
    }

    /**
     * Determine whether user can permanently delete model.
     */
    public function forceDelete(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Only admin can force delete laporan
        return $user->role->role_name === 'admin';
    }

    /**
     * Determine whether user can print laporan.
     */
    public function print(User $user, LaporanKegiatan $laporanKegiatan): bool
    {
        // Both admin and supervisor can print
        return in_array($user->role->role_name, ['admin', 'supervisor']);
    }
}
