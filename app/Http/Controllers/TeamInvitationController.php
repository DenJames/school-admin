<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;

class TeamInvitationController extends Controller
{
    public function destroy(Request $request, $invitationId)
    {
        $model = Jetstream::teamInvitationModel();

        $invitation = $model::whereKey($invitationId)->firstOrFail();

        if (Auth::user()->currentTeamRole() !== 'admin') {
            throw new AuthorizationException;
        }

        $invitation->delete();

        return redirect()->back(303);
    }
}
