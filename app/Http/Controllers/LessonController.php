<?php

namespace App\Http\Controllers;

use App\Data\LessonData;
use App\Models\Lesson;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Sabre\VObject\Component\VCalendar;

class LessonController extends Controller
{
    public function index()
    {
        $datetime = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));
        $datetime_string = $datetime->format('c');

        return Inertia::render('Lessons/Index', [
            'now' => $datetime_string,
            'uuid' => auth()->user()->uuid,
        ]);
    }

    public function show(Lesson $lesson)
    {
        Gate::authorize('view', $lesson);

        return Inertia::render('Lessons/Show', [
            'lesson' => LessonData::from($lesson->load([
                'classroomReservation.classroom',
                'classCategory',
                'team.school',
                'homeworks',
                'teacher' => function ($query) {
                    $query->with(['user', 'school']);
                }
            ])),
        ]);
    }

    public function json(Request $request)
    {
        $lessons = [];
        $user = $request->user();
        $currentLessons = $user
            ->currentTeam
            ->lessons()
            ->when($user->teacher, function ($query) use ($user) {
                $query->orWhere('teacher_id', $user->teacher->id);
            })
            ->whereBetween('starts_at', [$request->start, $request->end])
            ->get();


        foreach ($currentLessons as $lesson) {
            $lessons[] = [
                ...LessonData::from($lesson)->toArray(),
                'title' => $lesson->name . ' • ' . $lesson->teacher->user->name . ' • ' . $lesson->classroom()->name,
            ];
        }



        return response()->json($lessons);

    }

    public function export($uuid)
    {
        $vcalendar = new VCalendar();

        $start = new Carbon();
        $end = (clone($start))->addMonths(12);
        $start->subMonths();

        $user = User::where('uuid', $uuid)->firstOrFail();

        if (!$user->id) {
            abort(403);
        }

        $teams = $user->allTeams();

        foreach ($teams as $team) {
            $lessons = $team->lessons()->whereBetween('starts_at', [$start, $end])->get();

            foreach ($lessons as $lesson) {
                $dateTime = new \DateTime($lesson->starts_at, new \DateTimeZone('Europe/Copenhagen'));
                $vcalendar->add('VEVENT', [
                    'SUMMARY' => $lesson->name . ' • ' . $lesson->teacher->user->name . ' • ' . $lesson->classroom()->name,
                    'DTSTART' => $dateTime,
                    'DTEND' => new \DateTime($lesson->ends_at),
                ]);
            }
        }

        return response()->streamDownload(function () use ($vcalendar) {
            echo $vcalendar->serialize();
        }, Str::slug($user->name) . '.ics');
    }
}
