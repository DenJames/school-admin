<?php

namespace App\Http\Controllers;

use App\Data\HomeworkData;
use App\Data\HomeworkSubmissionData;
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
        if ($submission = $homework->submissions()->where('user_id', Auth::id())->first()) {
            $homeworkSubmission = HomeworkSubmissionData::fromModel($submission);
        }
        return Inertia::render('Homework/Show', [
            'homework' => HomeworkData::from($homework),
            'homeworkSubmission' => $homeworkSubmission ?? null,
            'files' => $homework->submissions()->where('user_id', Auth::id())->first()?->document()->orderBy('id', 'desc')->get(),
        ]);
    }

    public function store(Request $request, Homework $homework)
    {
        $request->validate([
            'content' => 'nullable',
        ]);

        if ($submission = $homework->submissions()->where('user_id', Auth::id())->first()) {
            $submission->update([
                'content' => $request->get('content'),
            ]);
        } else {
            $submission = HomeworkSubmission::create([
                'homework_id' => $homework->id,
                'user_id' => Auth::id(),
                'content' => $request->comment,
            ]);
        }


        // Store the file
        if ($request->hasFile('files')) {
            foreach ($request->file()['files'] as $item) {
                // if meme type is an executable file deny it
                if (!in_array($item->getClientOriginalExtension(), ['doc', 'docx', 'rtf', 'xls', 'csv', 'xlsx', 'ppt', 'pptx', 'pdf', 'txt', 'png', 'jpg', 'gif', 'jpeg', 'zip', 'rar', '7z', 'tar', 'gz', 'tgz', 'bz2', 'tbz', 'tbz2', 'tar.gz', 'tar.bz2', 'tar.gz2', 'mp4', 'mp3', 'mkv', 'json', 'xml', 'log', 'yml', 'yaml'])) {
                        abort(403, 'File type not allowed. (.'.$item->getClientOriginalExtension().')');
                    }
                $name = \Str::random(40).'.'.$item->extension();
                $path = \Storage::disk('public')->putFileAs('homeworks/'.Auth::user()->id, $item, $name);
                $submission->document()->create([
                    'filename' => $item->getClientOriginalName(),
                    'path' => $path,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('homework.show', $homework)->with('success', 'Homework submitted.');
    }
}
