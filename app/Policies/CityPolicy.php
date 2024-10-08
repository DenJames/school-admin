<?php

namespace App\Policies;

use App\Models\City;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function view(User $user, City $city): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function update(User $user, City $city): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function delete(User $user, City $city): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function restore(User $user, City $city): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function forceDelete(User $user, City $city): bool
    {
        return $user->hasAnyRole(['admin']);
    }
}
