<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VotingTime extends Model
{
    protected $fillable = ['start_at', 'end_at'];

    public function election() : BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function scopeOpen(Builder $query, Carbon $time)
    {
        return $query->where([
            ['start_at', '<=', $time],
            ['end_at', '>', $time],
        ]);
    }
}
