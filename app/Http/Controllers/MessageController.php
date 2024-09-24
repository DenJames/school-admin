<?php

namespace App\Http\Controllers;

use App\Data\MessageData;
use App\Http\Requests\MessageFormRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function index()
    {
        return Inertia::render('Messages/Index', [
            'messages' => MessageData::collect(Auth::user()?->messages()->latest()->with(['sender', 'receiver'])->get()),
            'receivedMessages' => MessageData::collect(Auth::user()?->receivedMessages()->with('sender')->get()),
        ]);
    }

    public function create()
    {
        return Inertia::render('Messages/Create');
    }


    public function show(Message $message)
    {
        if (!$message->hasAccess()) {
            abort(403);
        }

        $message->markAsRead();

        return Inertia::render('Messages/Show', [
            'message' => MessageData::from($message->load(['sender', 'receiver', 'replies' => fn($query) => $query->with('user')->latest()->get()])),
        ]);
    }

    public function store(MessageFormRequest $request): RedirectResponse
    {
        $receiver = User::find($request->input('recipient.id'));

        if (!$receiver || !$receiver->teams->contains($request->user()->currentTeam)) {
            return redirect()->back()->withErrors([
                'message' => 'The receiver does not exist.',
            ]);
        }

        $request->user()->messages()->create([
            'receiver_id' => $receiver->id,
            'subject' => $request->input('subject'),
            'content' => $request->input('content'),
        ]);

        return redirect()->route('messages.index')->with('success', 'Message sent.');
    }

    public function update(Message $message, MessageFormRequest $request)
    {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        $message->update([
            'subject' => $request->input('subject'),
            'content' => $request->input('content'),
        ]);

        return redirect()->back()->with('success', 'Message updated.');
    }

    public function destroy(Message $message): RedirectResponse
    {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();

        return redirect()->route('messages.index')->with('success', 'Message deleted.');
    }

    public function fetchRecipients(Request $request)
    {
        return User::select(['id', 'name'])
            ->where('id', '!=', $request->user()->id)
            ->whereHas('teams', function ($query) use ($request) {
                $query->where('teams.id', $request->user()->currentTeam->id);
            })
            ->where('name', 'like', '%' . $request->name . '%')
            ->get();
    }
}
