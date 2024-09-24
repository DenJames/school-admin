<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Sabre\VObject\Component\VCalendar;

class LessonController extends Controller
{
    public function index()
    {
        $datetime = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));
        $datetime_string = $datetime->format('c');

        return Inertia::render('Lessons', [
            'now' => $datetime_string,
        ]);
    }

    public function json(Request $request)
    {
        $lessons = [];

        foreach (Auth::user()->currentTeam->lessons()->whereBetween('starts_at', [$request->start, $request->end])->get() as $leasson) {
            $lessons[] = [
                'title' => $leasson->name . ' • ' . $leasson->teacher->user->name . ' • ' . $leasson->classroom()->name,
                'start' => $leasson->starts_at->format('c'),
                'end' => $leasson->ends_at->format('c'),
                'description' => "Hello",
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

        if(!$user->id){
            abort(403);
        }

        $teams = $user->allTeams();

        foreach ($teams as $team) {
            $lessons = $team->lessons()->whereBetween('starts_at', [$start, $end])->get();

            foreach($lessons as $event){
                $dateTime = new \DateTime($event->date_start, new \DateTimeZone('Europe/Copenhagen'));
                $vevent = $vcalendar->add('VEVENT', [
                    'SUMMARY' => $event->name . ' • ' . $event->teacher->user->name . ' • ' . $event->classroom()->name,
                    'DTSTART' => $dateTime,
                    'DTEND'   => new \DateTime($event->date_end),
                ]);

                if(isset($event->rrule)){
                    $vevent->add('RRULE', urldecode($event->rrule));
                }
            }
        }


        return response()->streamDownload(function() use($vcalendar) {
            echo $vcalendar->serialize();
        }, Str::slug($user->name).'.ics');
    }
}
