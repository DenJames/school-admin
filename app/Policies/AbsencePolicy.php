<?php

namespace App\Policies;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AbsencePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Absence $absence): bool
    {
        return $user->hasRole('admin') || $user->id === $absence->user_id || $user->id === $absence->teacher->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Absence $absence): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Absence $absence): bool
    {
        if ($absence->approved_at) {
            return $user->hasAnyRole(['admin', 'teacher']);
        }

        return $user->hasAnyRole(['admin', 'teacher']) || $user->id === $absence->user_id;
    }

    public function restore(User $user, Absence $absence): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Absence $absence): bool
    {
        return $user->hasRole('admin');
    }

    public function updateStatus(User $user, Absence $absence): bool
    {
        return $user->hasRole('admin') || $user->id === $absence->teacher->user_id;
    }
}
