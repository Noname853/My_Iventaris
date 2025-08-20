<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Alat;
use App\Models\User;

class AlatPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua user yang sudah login bisa melihat daftar alat
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Alat $alat): bool
    {
        return true; // Semua user yang sudah login bisa melihat detail alat
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin(); // Hanya admin yang bisa membuat alat baru
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Alat $alat): bool
    {
        return $user->isAdmin(); // Hanya admin yang bisa mengupdate alat
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Alat $alat): bool
    {
        return $user->isAdmin(); // Hanya admin yang bisa menghapus alat
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Alat $alat): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Alat $alat): bool
    {
        return $user->isAdmin();
    }
}
