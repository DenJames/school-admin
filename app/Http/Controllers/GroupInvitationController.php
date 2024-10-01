<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupInvitationFormRequest;
use App\Mail\GroupInvitationMail;
use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GroupInvitationController extends Controller
{
    public function store(GroupInvitationFormRequest $request, Group $group): RedirectResponse
    {
        if (!Auth::user()?->isCurrentGroupAdmin()) {
            throw new AuthorizationException;
        }

        $receiver = User::find($request->input('recipient.id'));
        if ($group->users->contains($receiver)) {
            throw ValidationException::withMessages([
                'recipient' => 'User is already a member',
            ])->errorBag('addGroupMember');
        }

        if ($group->invitations->contains('email', $receiver->email)) {
            throw ValidationException::withMessages([
                'recipient' => 'User is already invited',
            ])->errorBag('addGroupMember');
        }

        $invitation = $group->invitations()->create([
            'user_id' => $receiver->id,
            'email' => $receiver->email,
            'group_role_id' => $request->input('group_role_id'),
            'token' => Str::uuid7(),
        ]);

        // Send mail to receiver with a link to accept the invitation
        Mail::to($receiver)->send(new GroupInvitationMail($invitation, route('groups.invite.accept', $invitation->token)));

        return redirect()->back()->with('success', 'Invitation sent');
    }

    public function accept(string $token)
    {
        $invitation = GroupInvitation::where('token', $token)->firstOrFail();

        if ($invitation->user_id !== Auth::id()) {
            throw new AuthorizationException;
        }

        $invitation->group->users()->attach($invitation->user_id, ['group_role_id' => $invitation->group_role_id]);

        $invitation->user()->update([
            'current_group_id' => $invitation->group_id,
        ]);

        $invitation->delete();



        return redirect()->route('dashboard')->with('success', 'Invitation accepted');
    }

    public function destroy(GroupInvitation $groupInvitation): RedirectResponse
    {
        if (!Auth::user()?->isCurrentGroupAdmin()) {
            throw new AuthorizationException;
        }

        $groupInvitation->delete();

        return redirect()->back()->with('success', 'Invitation deleted');
    }
}
