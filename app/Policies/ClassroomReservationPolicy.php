<?php

namespace App\Policies;

use App\Models\ClassroomReservation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassroomReservationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function view(User $user, ClassroomReservation $classroomReservation): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function update(User $user, ClassroomReservation $classroomReservation): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function delete(User $user, ClassroomReservation $classroomReservation): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function restore(User $user, ClassroomReservation $classroomReservation): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }

    public function forceDelete(User $user, ClassroomReservation $classroomReservation): bool
    {
        return $user->hasAnyRole(['admin', 'school', 'teacher']);
    }
}
