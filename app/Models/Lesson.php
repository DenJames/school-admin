<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classCategory(): BelongsTo
    {
        return $this->belongsTo(ClassCategory::class);
    }

    public function classroomReservation(): BelongsTo
    {
        return $this->belongsTo(ClassroomReservation::class);
    }

    // Shortcut to get the classroom of the lesson
    public function classroom(): Classroom
    {
        return $this->classroomReservation->classroom;
    }
}
