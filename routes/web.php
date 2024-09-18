<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $datetime = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));
        $datetime_string = $datetime->format('c');

        $lessons = [];

        foreach (Auth::user()->currentTeam->lessons()->where('starts_at', '>', \Carbon\Carbon::now()->startOfDay())->get() as $leasson) {
            $lessons[] = [
                'title' => $leasson->name . ' • ' . $leasson->teacher->user->name . ' • ' . $leasson->classroom()->name,
                'start' => $leasson->starts_at->format('c'),
                'end' => $leasson->ends_at->format('c'),
                'description' => "Hello",
            ];
        }

        return Inertia::render('Dashboard', [
            'now' => $datetime_string,
            'lessons' => $lessons,
        ]);
    })->name('dashboard');

    Route::get('/lessons', function () {
        $datetime = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));
        $datetime_string = $datetime->format('c');

        $lessons = [];

        foreach (Auth::user()->currentTeam->lessons()->get() as $leasson) {
            $lessons[] = [
                'title' => $leasson->name . ' • ' . $leasson->teacher->user->name . ' • ' . $leasson->classroom()->name,
                'start' => $leasson->starts_at->format('c'),
                'end' => $leasson->ends_at->format('c'),
                'description' => "Hello",
            ];
        }

        return Inertia::render('Lessons', [
            'now' => $datetime_string,
            'lessons' => $lessons,
        ]);
    })->name('lessons.index');
});

