<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMemberController extends Controller
{
    public function update(Request $request, Group $group)
    {
        $group->members()->where('user_id', (int) $request->input('id'))->update([
            'group_role_id' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Members updated successfully');
    }

    public function destroy(Group $group, User $user): RedirectResponse
    {
        if (!$group->isOwner() && !$user->isCurrentGroupAdmin() && $user->id !== Auth::id()) {
            abort(403);
        }

        $group->members()->where('user_id', $user->id)->delete();

        $user?->update([
            'current_group_id' => $user->groups->first()?->id,
        ]);

        if ($user->id === Auth::id()) {
            return to_route('dashboard')->with('success', 'You left the group');
        }

        return redirect()->back()->with('success', 'Member removed successfully');
    }
}
