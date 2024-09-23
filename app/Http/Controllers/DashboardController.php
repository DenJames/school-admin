<?php

namespace App\Http\Controllers;

use App\Data\MessageData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $datetime = new \DateTime('now', new \DateTimeZone('Europe/Copenhagen'));
        $datetime_string = $datetime->format('c');

        $teams = "";

        foreach (Auth::user()->allTeams() as $team) {
            $teams .= $team->name . " ";
        }

        return Inertia::render('Dashboard', [
            'now' => $datetime_string,
            'teams' => $teams,
            'receivedMessages' => MessageData::collect(Auth::user()?->receivedMessages()->with('sender')->get()),
        ]);
    }
}
