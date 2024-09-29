<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }

    public function restore(User $user, User $model): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }
}
