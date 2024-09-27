<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class AbsenceController extends Controller
{
    public function store(Request $request, Lesson $lesson)
    {
        $request->validate([
            'reason' => ['required', 'string'],
        ]);

        if ($lesson->absences()->where('user_id', $request->user()->id)->exists()) {
            throw ValidationException::withMessages([
                'reason' => 'Du har allerede meldt fravær for denne lektion.',
            ])->errorBag('absenceForm');
        }

        $lesson->absences()->create([
            'user_id' => $request->user()->id,
            'teacher_id' => $lesson->teacher_id,
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Fravær er blevet registreret.');
    }

    public function destroy(Absence $absence)
    {
        Gate::authorize('delete', $absence);
        
        $absence->delete();

        return redirect()->back()->with('success', 'Fravær er blevet slettet.');
    }
}
