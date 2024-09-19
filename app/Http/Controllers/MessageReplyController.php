<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageReplyFormRequest;
use App\Models\Message;
use App\Models\MessageReply;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MessageReplyController extends Controller
{
    public function store(Message $message, MessageReplyFormRequest $request): RedirectResponse
    {
        if (!$message->hasAccess()) {
            abort(403);
        }

        $message->replies()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        return redirect()->back()->with('success', 'Reply sent.');
    }

    public function destroy(MessageReply $reply)
    {
        if ($reply->user()->isNot(Auth::user())) {
            abort(403);
        }

        $reply->delete();

        return redirect()->back()->with('success', 'Reply deleted.');
    }
}
