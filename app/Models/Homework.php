<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class Homework extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function submissions()
    {
        return $this->hasMany(HomeworkSubmission::class);
    }

    // Shortcut to grab the teacher whom the lesson belongs to
    public function teacher(): Teacher
    {
        return $this->lesson->teacher;
    }

    public function isSubmitted(): bool
    {
        return $this->submissions()->where('user_id', Auth::user()->id)->get()->isNotEmpty();
    }
}
