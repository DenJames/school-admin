<?php

namespace App\Policies;

use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CountryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function view(User $user, Country $country): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function update(User $user, Country $country): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function delete(User $user, Country $country): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function restore(User $user, Country $country): bool
    {
        return $user->hasAnyRole(['admin']);
    }

    public function forceDelete(User $user, Country $country): bool
    {
        return $user->hasAnyRole(['admin']);
    }
}
