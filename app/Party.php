<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Party extends Model
{
    protected $fillable = ['name', 'faculty_id', 'number'];

    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function members() : HasManyThrough
    {
        return $this->hasManyThrough(Student::class, Candidate::class, 'party_id', 'id', 'id', 'student_id');
    }

    public function candidates() : HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function scopeByName(Builder $query, string $name)
    {
        return $query->where('name', $name);
    }
}
