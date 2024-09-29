<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function view(User $user, Assignment $assignment): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']) || $user->id === $assignment->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function update(User $user, Assignment $assignment): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function restore(User $user, Assignment $assignment): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function forceDelete(User $user, Assignment $assignment): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }
}
