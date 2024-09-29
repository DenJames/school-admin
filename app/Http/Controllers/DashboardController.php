<?php

namespace App\Http\Controllers;

use App\Data\ArticleData;
use App\Data\MessageData;
use App\Models\Article;
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

        $articles = ArticleData::collect(Article::where('is_global', true)
            ->orWhere('school_id', Auth::user()->currentTeam->school_id)
            ->orWhere('team_id', Auth::user()->current_team_id)
            ->whereNotNull('published_at')
            ->with('user', 'school', 'team')
            ->get());

        return Inertia::render('Dashboard', [
            'now' => $datetime_string,
            'teams' => $teams,
            'receivedMessages' => MessageData::collect(Auth::user()?->receivedMessages()->with('sender')->get()),
            'articles' => $articles,
        ]);
    }
}
