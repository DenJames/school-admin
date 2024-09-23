<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
}
