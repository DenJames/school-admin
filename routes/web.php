<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupInvitationController;
use App\Http\Controllers\GroupMemberController;
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


    // Groups
    Route::put('/groups/switch', [GroupController::class, 'switch'])->name('groups.switch');
    Route::resource('groups', GroupController::class);
    Route::post('/groups/{group}/invite', [GroupInvitationController::class, 'store'])->name('groups.invite.store');
    Route::delete('/groups/{groupInvitation}/invite', [GroupInvitationController::class, 'destroy'])->name('groups.invite.destroy');
    Route::get('/groups/invite/{token}/accept', [GroupInvitationController::class, 'accept'])->name('groups.invite.accept');

    Route::put('/groups/{group}/member/update', [GroupMemberController::class, 'update'])->name('group.member.update');

    // Lessons
    Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::group(['prefix' => 'api', 'name' => 'api'], function () {
        Route::get('/lessons', [LessonController::class, 'json'])->name('lessons.json');
    });
});

