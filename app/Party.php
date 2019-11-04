<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Party extends Model
{
    protected $fillable = ['name', 'faculty_id', 'number'];

    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function members() : HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function scopeByName(Builder $query, string $name)
    {
        return $query->where('name', $name);
    }
}
