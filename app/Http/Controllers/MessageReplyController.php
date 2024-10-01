<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageReplyFormRequest;
use App\Models\Message;
use App\Models\MessageReply;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MessageReplyController extends Controller
{
    public function store(Message $message, MessageReplyFormRequest $request): RedirectResponse
    {
        if (!$message->hasAccess()) {
            throw new AuthorizationException;
        }

        $message->replies()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content'),
        ]);

        return redirect()->back()->with('success', 'Reply sent.');
    }

    public function update(MessageReply $reply, MessageReplyFormRequest $request): RedirectResponse
    {
        if ($reply->user()->isNot(Auth::user())) {
            throw new AuthorizationException;
        }

        $reply->update([
            'content' => $request->input('content'),
        ]);

        return redirect()->back()->with('success', 'Reply updated.');
    }

    public function destroy(MessageReply $reply): RedirectResponse
    {
        if ($reply->user()->isNot(Auth::user())) {
            throw new AuthorizationException;
        }

        $reply->delete();

        return redirect()->back()->with('success', 'Reply deleted.');
    }
}
