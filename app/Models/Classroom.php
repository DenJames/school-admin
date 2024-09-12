<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reservations(): HasMany
    {
        return $this->hasMany(ClassroomReservation::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function lessons(string|null $from = null, string|null $to = null)
    {
        $reservations = $this->reservations;

        if ($from) {
            $reservations = $reservations->where('booked_from', '>=', $from);
        }

        if ($to) {
            $reservations = $reservations->where('booked_to', '<=', $to);
        }

        return $reservations->load('lessons')->pluck('lessons')->flatten();
    }
}
