<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }

    public function view(User $user, Message $message): bool
    {
        return $user->id === $message->user_id || $user->hasAnyRole(['admin', 'school']);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Message $message): bool
    {
        return $user->id === $message->user_id || $user->hasAnyRole(['admin', 'school']);
    }

    public function delete(User $user, Message $message): bool
    {
        return $user->id === $message->user_id || $user->hasAnyRole(['admin', 'school']);
    }

    public function restore(User $user, Message $message): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }

    public function forceDelete(User $user, Message $message): bool
    {
        return $user->hasAnyRole(['admin', 'school']);
    }
}
