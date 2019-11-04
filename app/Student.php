<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = ['name', 'surname', 'student_id', 'faculty_id', 'program_id', 'status'];

    public function program() : BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function scopeBySID(Builder $query, string $sid)
    {
        return $query->where('student_id', $sid);
    }
}
