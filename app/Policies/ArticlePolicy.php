<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Article $article): bool
    {
        return $user->hasRole('admin') || $user->id === $article->user_id || $user->currentTeam->school_id === $article->school_id || $user->current_team_id === $article->team_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('school');
    }

    public function update(User $user, Article $article): bool
    {
        return $user->hasRole('admin') || $user->id === $article->user_id;
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->hasRole('admin') || $user->id === $article->user_id;
    }

    public function restore(User $user, Article $article): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Article $article): bool
    {
        return $user->hasRole('admin');
    }
}
