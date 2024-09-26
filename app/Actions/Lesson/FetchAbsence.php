<?php

namespace App\Actions\Lesson;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class FetchAbsence
{
    public function __construct(public Lesson $lesson)
    {
    }

    public function all()
    {
        $isTeacher = Auth::id() === $this->lesson->teacher->user_id;
        if (!$isTeacher && !Auth::user()?->hasRole('admin')) {
            return $this->lesson->absences()->where('user_id', Auth::id())->with('user:id,name,email,profile_photo_path')->get();
        }

        return $this->lesson->absences->load('user:id,name,email,profile_photo_path');
    }
}
