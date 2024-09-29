<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassroomPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function view(User $user, Classroom $classroom): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }

    public function update(User $user, Classroom $classroom): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }

    public function delete(User $user, Classroom $classroom): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }

    public function restore(User $user, Classroom $classroom): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }

    public function forceDelete(User $user, Classroom $classroom): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }
}
