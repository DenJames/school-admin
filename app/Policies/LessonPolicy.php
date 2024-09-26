<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LessonPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Lesson $lesson): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return false;
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return false;
    }

    public function restore(User $user, Lesson $lesson): bool
    {
        return true;
    }

    public function forceDelete(User $user, Lesson $lesson): bool
    {
        return true;
    }
}
