<?php

namespace App\Http\Controllers;

use App\Data\HomeworkData;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeworkController extends Controller
{
    public function index()
    {
        return Inertia::render('Homework/Index', [
            'homework' => HomeworkData::collect(Auth::user()?->homeworks()),
        ]);
    }

    public function show(Homework $homework)
    {
        return Inertia::render('Homework/Show', [
            'homework' => HomeworkData::from($homework),
            'file' => $homework->submissions()->where('user_id', Auth::id())->first()?->document()->orderBy('id', 'desc')->first(),
        ]);
    }

    public function store(Request $request, Homework $homework)
    {
        $request->validate([
            'comment' => 'nullable',
        ]);

        if ($submission = $homework->submissions()->where('user_id', Auth::id())->first()) {
            $submission->update([
                'content' => $request->comment,
            ]);
        } else {
            $submission = HomeworkSubmission::create([
                'homework_id' => $homework->id,
                'user_id' => Auth::id(),
                'content' => $request->comment,
            ]);
        }


        // Store the file
        if ($request->hasFile('file')) {
            $name = \Str::random(40).'.'.$request->file('file')->extension();
            \Storage::putFileAs('public/homeworks/'.Auth::user()->id, $request->file('file'), $name);
            $path = 'homeworks/'.Auth::user()->id.'/'.$name;
            $submission->document()->create([
                'filename' => $request->file('file')->getClientOriginalName(),
                'path' => $path,
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('homework.show', $homework)->with('success', 'Homework submitted.');
    }
}
