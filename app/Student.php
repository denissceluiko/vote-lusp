<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Student extends Model
{
    protected $fillable = ['name', 'surname', 'sid', 'faculty_id', 'program_id', 'status', 'phone', 'email'];

    public function program() : BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function party() : HasOneThrough
    {
        return $this->hasOneThrough(Party::class, Candidate::class);
    }

    public function scopeBySID(Builder $query, string $sid)
    {
        return $query->where('sid', $sid);
    }

    public function scopeSearch(Builder $query, string $key)
    {
        return $query->where('name', 'like', "%$key%")
            ->orWhere('surname', 'like', "%$key%")
            ->orWhere('sid', 'like', "%$key%");
    }
}
