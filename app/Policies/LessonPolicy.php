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
        return $user->hasRole('admin');
    }

    public function view(User $user, Lesson $lesson): bool
    {
        return $user->hasRole('admin') || $user->current_team_id === $lesson->team_id || $lesson->teacher->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('teacher');
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return $user->hasRole('admin') || $lesson->teacher->user_id === $user->id;
    }

    public function delete(User $user, Lesson $lesson): bool
    {
        return $user->hasRole('admin') || $lesson->teacher->user_id === $user->id;
    }

    public function restore(User $user, Lesson $lesson): bool
    {
        return $user->hasRole('admin') || $lesson->teacher->user_id === $user->id;
    }

    public function forceDelete(User $user, Lesson $lesson): bool
    {
        return $user->hasRole('admin') || $lesson->teacher->user_id === $user->id;
    }
}
