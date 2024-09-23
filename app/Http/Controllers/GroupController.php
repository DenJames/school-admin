<?php

namespace App\Http\Controllers;

use App\Http\Requests\GroupFormRequest;
use App\Models\Group;
use App\Models\GroupRole;
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

        $group->members()->create([
            'user_id' => Auth::id(),
            'group_role_id' => 1,
        ]);

        $group->setToCurrent();

        return redirect()->back()->with('success', 'Group created successfully');
    }

    public function show(Group $group)
    {
        return Inertia::render('Groups/Show', [
            'group' => $group->load('members.user'),
            'availableRoles' => GroupRole::all(),
        ]);
    }

    public function edit(Group $group)
    {
    }

    public function update(GroupFormRequest $request, Group $group)
    {
    }

    public function destroy(Group $group)
    {
        if (!$group->isOwner()) {
            abort(403);
        }

    }
}
