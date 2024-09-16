<?php

namespace App\Http\Controllers;

use App\Data\MessageData;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index()
    {
        return Inertia::render('Messages/Index', [
            'messages' => MessageData::collect(Auth::user()?->messages()->with(['sender', 'receiver'])->get()),
            'receivedMessages' => MessageData::collect(Auth::user()?->receivedMessages()->with('sender')->get()),
        ]);
    }

    public function show(Message $message)
    {
        if (!$message->hasAccess()) {
            abort(403);
        }

        return Inertia::render('Messages/Show', [
            'message' => MessageData::from($message->load(['sender', 'receiver', 'replies.user'])),
        ]);
    }
}
