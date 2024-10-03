<?php

namespace App\Policies;

use App\Models\School;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchoolPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function view(User $user, School $school): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function update(User $user, School $school): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function delete(User $user, School $school): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function restore(User $user, School $school): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function forceDelete(User $user, School $school): bool
    {
        return $user->hasAnyRole(['admin']);
    }
}
