<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    protected $fillable = ['name', 'lri', 'code'];

    /**
     * @return BelongsTo
     */
    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students() : HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function scopeByName(Builder $query, string $name) {
        return $query->where('name', $name);
    }

    public function scopeByCode(Builder $query, string $code) {
        return $query->where('code', $code);
    }
}
