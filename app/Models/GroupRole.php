<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupRole extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function groupMembers(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }
}
