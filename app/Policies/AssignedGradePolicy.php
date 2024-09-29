<?php

namespace App\Policies;

use App\Models\AssignedGrade;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignedGradePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function view(User $user, AssignedGrade $assignedGrade): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']) || $user->id === $assignedGrade->user_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function update(User $user, AssignedGrade $assignedGrade): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function delete(User $user, AssignedGrade $assignedGrade): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function restore(User $user, AssignedGrade $assignedGrade): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function forceDelete(User $user, AssignedGrade $assignedGrade): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }
}
