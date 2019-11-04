<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    protected $fillable = ['name', 'abbreviation'];

    /**
     * @return HasMany
     */
    public function programs() : HasMany
    {
        return $this->hasMany(Program::class);
    }

    public function students() : HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function parties() : HasMany
    {
        return $this->hasMany(Party::class);
    }

    public function scopeByName(Builder $query, string $name) {
        return $query->where('name', $name);
    }

    public function scopeByAbbreviation(Builder $query, string $abbreviation) {
        return $query->where('abbreviation', $abbreviation);
    }
}
