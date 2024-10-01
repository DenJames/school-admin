<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\DeletesTeams;

class DeleteTeam implements DeletesTeams
{
    /**
     * Delete the given team.
     */
    public function delete(Team $team): void
    {
        if (!Auth::user()->ownsTeam($team)) {
            throw new AuthorizationException;
        }

        $team->purge();
    }
}
