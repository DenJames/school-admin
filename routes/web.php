<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageReplyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/message/receiver', [MessageController::class, 'fetchRecipients'])->name('message.receiver');

    Route::resource('messages', MessageController::class);

    Route::post('/message/{message}/reply', [MessageReplyController::class, 'store'])->name('message.reply');
    Route::put('/message/{reply}/update', [MessageReplyController::class, 'update'])->name('message.reply.update');
    Route::delete('/message/{reply}/delete', [MessageReplyController::class, 'destroy'])->name('message.reply.destroy');

    Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');

    Route::get('/homework', [HomeworkController::class, 'index'])->name('homework.index');
    Route::get('/homework/{homework}', [HomeworkController::class, 'show'])->name('homework.show');
    Route::post('/homework/{homework}', [HomeworkController::class, 'store'])->name('homework.store');

    Route::group(['prefix' => 'document', 'as' => 'document.'], function () {
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        Route::get('/{document}/view', [DocumentController::class, 'view'])->name('view');
        Route::delete('/{document}/delete', [DocumentController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'api', 'name' => 'api.'], function () {
        Route::get('/lessons', [LessonController::class, 'json'])->name('lessons.json');
    });
});

Route::get('/export/lessons/{uuid}', [LessonController::class, 'export'])->name('lessons.export');

