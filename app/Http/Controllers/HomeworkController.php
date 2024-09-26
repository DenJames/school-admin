<?php

namespace App\Http\Controllers;

use App\Data\HomeworkData;
use App\Data\HomeworkSubmissionData;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $homeworkSubmission = $homework->submissions()->where('user_id', Auth::id())->first();
        return Inertia::render('Homework/Show', [
            'homework' => HomeworkData::from($homework),
            'homeworkSubmission' => $homeworkSubmission ? HomeworkSubmissionData::from($homeworkSubmission) : null,
            'files' => $homework->submissions()->where('user_id', Auth::id())->first()?->document()->orderByDesc(('id'))->get(),
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
                'content' => $request->get('content'),
            ]);
        }

        // Store the file
        if ($request->hasFile('files')) {
            $allowedExtensions = ['doc', 'docx', 'rtf', 'xls', 'csv', 'xlsx', 'ppt', 'pptx', 'pdf', 'txt', 'png', 'jpg', 'gif', 'jpeg', 'zip', 'rar', '7z', 'tar', 'gz', 'tgz', 'bz2', 'tbz', 'tbz2', 'tar.gz', 'tar.bz2', 'tar.gz2', 'mp4', 'mp3', 'mkv', 'json', 'xml', 'log', 'yml', 'yaml'];

            foreach ($request->file()['files'] as $item) {
                // if file type is an executable file deny it
                if (!in_array($item->getClientOriginalExtension(), $allowedExtensions, true)) {
                        abort(403, 'File type not allowed. (.'.$item->getClientOriginalExtension().')');
                    }
                $name = Str::random(40).'.'.$item->extension();
                $path = Storage::disk('public')->putFileAs('homeworks/'.Auth::user()->id, $item, $name);
                $submission->document()->create([
                    'filename' => $item->getClientOriginalName(),
                    'path' => $path,
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Homework submitted.');
    }
}
