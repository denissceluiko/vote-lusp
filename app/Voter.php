<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Voter extends Model
{
    protected $fillable = ['student_id', 'election_id'];

    public function election() : BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function student() : BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function ballot() : HasOne
    {
        return $this->hasOne(Ballot::class);
    }

    public function scopeStudent(Builder $query, Student $student)
    {
        return $query->where('student_id', $student->id);
    }
}
