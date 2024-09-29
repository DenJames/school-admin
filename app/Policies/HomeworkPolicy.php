<?php

namespace App\Policies;

use App\Models\Homework;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HomeworkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function view(User $user, Homework $homework): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']) || $homework->lesson->team->users->contains($user);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function update(User $user, Homework $homework): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function delete(User $user, Homework $homework): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function restore(User $user, Homework $homework): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function forceDelete(User $user, Homework $homework): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }
}
