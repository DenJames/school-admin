<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Lesson;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AbsenceController extends Controller
{

    public function index()
    {
        $absences = Auth::user()?->absences()
            ->join('lessons as l', 'absences.lesson_id', '=', 'l.id')
            ->join('teams', 'teams.id', '=', 'l.team_id')
            ->select('teams.id as team_id')
            ->addSelect('teams.name as team_name')
            ->addSelect(DB::raw('COUNT(absences.id) as total_absence_count'))
            ->addSelect(['total_lessons' => function ($query) {
                $query->select(DB::raw('COUNT(*)'))
                    ->from('lessons')
                    ->whereColumn('lessons.team_id', 'teams.id');
            }])
            ->addSelect(DB::raw('(COUNT(absences.id) / (SELECT COUNT(*) FROM lessons WHERE lessons.team_id = teams.id)) * 100 as absence_for_lesson_percentage'))
            ->addSelect(['total_lessons_year' => function ($query) {
                $query->select(DB::raw('COUNT(*)'))
                    ->from('lessons')
                    ->whereColumn('lessons.team_id', 'teams.id')
                    ->whereRaw('YEAR(lessons.starts_at) = YEAR(CURDATE())');
            }])
            ->addSelect(['absence_count_year' => function ($query) {
                $query->select(DB::raw('COUNT(*)'))
                    ->from('absences')
                    ->join('lessons as les', 'absences.lesson_id', '=', 'les.id')
                    ->whereColumn('les.team_id', 'teams.id')
                    ->whereRaw('YEAR(les.starts_at) = YEAR(CURDATE())')
                    ->where('absences.user_id', Auth::user()->id);
            }])
            ->addSelect(['absence_for_year_percentage' => function ($query) {
                $query->select(DB::raw('(COUNT(*) / (SELECT COUNT(*) FROM lessons WHERE lessons.team_id = teams.id AND YEAR(lessons.starts_at) = YEAR(CURDATE()))) * 100'))
                    ->from('absences')
                    ->join('lessons as les', 'absences.lesson_id', '=', 'les.id')
                    ->whereColumn('les.team_id', 'teams.id')
                    ->whereRaw('YEAR(les.starts_at) = YEAR(CURDATE())')
                    ->where('absences.user_id', Auth::user()->id);
            }])
            ->where('absences.user_id', Auth::user()->id)
            ->groupBy('teams.id', 'teams.name')
            ->get();

        return Inertia::render('Absence/Index', [
            'absences' => $absences,
        ]);
    }

    public function show(Team $team)
    {
        $absences = Auth::user()?->absences()
            ->join('lessons as l', 'absences.lesson_id', '=', 'l.id')
            ->join('users as u', 'l.teacher_id', '=', 'u.id')
            ->where('l.team_id', $team->id)
            ->select('l.id as lesson_id')
            ->addSelect('l.starts_at')
            ->addSelect(['ends_at' => function ($query) {
                $query->select(DB::raw('DATE_ADD(l.starts_at, INTERVAL l.duration MINUTE) as ends_at'))
                    ->from('lessons')
                    ->whereColumn('lessons.id', 'l.id');
            }])
            ->addSelect('l.name as lesson_name')
            ->addSelect('u.name as teacher_name')
            ->addSelect('absences.reason')
            ->addSelect('absences.excused')
            ->orderBy('l.starts_at', 'desc')
            ->get();

        return Inertia::render('Absence/Show', [
            'team' => $team,
            'absences' => $absences,
        ]);
    }


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
