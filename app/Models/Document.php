<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['url'];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute()
    {
        return \Storage::url($this->path);
    }
}
