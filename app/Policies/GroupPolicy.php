<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function view(User $user, Group $group): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']) || $group->users->contains($user);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher', 'student']);
    }

    public function update(User $user, Group $group): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']) || $user->isCurrentGroupAdmin();
    }

    public function delete(User $user, Group $group): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']) || $group->isOwner();
    }

    public function restore(User $user, Group $group): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']) || $group->isOwner();
    }

    public function forceDelete(User $user, Group $group): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']) || $group->isOwner();
    }
}
