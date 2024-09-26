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

        if ($this->lesson->absences->isEmpty()) {
            return [];
        }

        if (Auth::id() !== $this->lesson->teacher->user_id && !Auth::user()?->hasRole('admin')) {
            return collect([$this->lesson->absences()->where('user_id', Auth::id())->with('user:id,name,email')->first()]);
        }

        return $this->lesson->absences->load('user:id,name,email');
    }
}
