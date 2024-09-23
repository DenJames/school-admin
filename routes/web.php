<?php

use App\Http\Controllers\LessonController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageReplyController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return to_route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $datetime = new DateTime('now', new DateTimeZone('Europe/Copenhagen'));
        $datetime_string = $datetime->format('c');

        return Inertia::render('Dashboard', [
            'now' => $datetime_string,
        ]);
    })->name('dashboard');

    Route::get('/message/receiver', [MessageController::class, 'fetchRecipients'])->name('message.receiver');

    Route::resource('messages', MessageController::class);

    Route::post('/message/{message}/reply', [MessageReplyController::class, 'store'])->name('message.reply');
    Route::put('/message/{reply}/update', [MessageReplyController::class, 'update'])->name('message.reply.update');
    Route::delete('/message/{reply}/delete', [MessageReplyController::class, 'destroy'])->name('message.reply.destroy');

    Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');

    Route::group(['prefix' => 'api', 'name' => 'api'], function () {
        Route::get('/lessons', [LessonController::class, 'json'])->name('lessons.json');
    });
});

