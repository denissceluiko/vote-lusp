<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidate extends Model
{
    protected $fillable = ['email', 'phone', 'student_id', 'party_id', 'votes_for', 'votes_against'];

    public function party() : BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function student() : BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeByVotes(Builder $query)
    {
        return $query->orderByRaw("`votes_for` - `votes_against` DESC");
    }
}
