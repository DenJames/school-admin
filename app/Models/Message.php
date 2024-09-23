<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(MessageReply::class);
    }

    public function markAsRead(): void
    {
        if ($this->read_at || $this->receiver_id !== Auth::id()) {
            return;
        }

        $this->update(['read_at' => now()]);
    }

    public function hasAccess(): bool
    {
        return $this->user_id === Auth::id() || $this->receiver_id === Auth::id();
    }
}
