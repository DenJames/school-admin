<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupFormRequest;
use App\Models\Group;
use App\Models\GroupRole;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GroupController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        return Inertia::render('Groups/Create');
    }

    public function store(GroupFormRequest $request)
    {
        $group = Auth::user()->currentTeam->groups()->create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);

        $adminRole = GroupRole::where('name', 'admin')->firstOrCreate([
            'name' => 'admin',
        ]);


        $group->members()->create([
            'user_id' => Auth::id(),
            'group_role_id' => $adminRole->id,
        ]);

        $group->setToCurrent();

        return to_route('groups.show', $group)->with('success', 'Group created successfully');
    }

    public function show(Group $group)
    {
        if (!$group->users->contains(Auth::id())) {
            throw new AuthorizationException;
        }

        return Inertia::render('Groups/Show', [
            'group' => $group->load(['users', 'owner', 'invitations.user']),
            'availableRoles' => GroupRole::all(),
        ]);
    }

    public function update(GroupFormRequest $request, Group $group)
    {
        if (!$group->isOwner()) {
            throw new AuthorizationException;
        }

        $group->update($request->validated());

        return redirect()->back()->with('success', 'Group updated successfully');
    }

    public function destroy(Group $group)
    {
        if (!$group->isOwner()) {
            throw new AuthorizationException;
        }

        $user = Auth::user();

        $group->delete();

        $user?->update([
            'current_group_id' => $user->groups->first()?->id,
        ]);

        return to_route('dashboard')->with('success', 'Group deleted successfully');
    }

    public function switch(Request $request)
    {
        if (!$request->user()->groups->contains($request->group_id)) {
            throw new AuthorizationException;
        }

        $request->user()->update([
            'current_group_id' => $request->group_id,
        ]);
    }
}
