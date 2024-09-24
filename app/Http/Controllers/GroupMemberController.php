<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    public function update(Request $request, Group $group)
    {
        $group->members()->where('user_id', (int) $request->input('id'))->update([
            'group_role_id' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Members updated successfully');
    }
}
