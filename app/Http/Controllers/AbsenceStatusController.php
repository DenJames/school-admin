<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AbsenceStatusController extends Controller
{
    public function __invoke(Request $request, Absence $absence)
    {
        Gate::authorize('updateStatus', $absence);

        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $absence->update([
            'excused' => $request->status,
            'approved_at' => $absence->approved_at ?? now(),
        ]);

        return redirect()->back();
    }
}
