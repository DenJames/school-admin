<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

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

    public function homeworks(): HasMany
    {
        return $this->hasMany(Homework::class);
    }

    public function getEndsAtAttribute()
    {
        return $this->starts_at->addMinutes($this->duration);
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }
}
