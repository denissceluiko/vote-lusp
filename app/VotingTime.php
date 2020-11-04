<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VotingTime extends Model
{
    protected $fillable = ['start_at', 'end_at'];
    public $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function election() : BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    public function formatted(string $format, string $dateformat)
    {
        $format = str_replace('%from', $this->start_at->format($dateformat), $format);
        $format = str_replace('%to', $this->end_at->format($dateformat), $format);
        return $format;
    }

    public function scopeOpen(Builder $query, Carbon $time)
    {
        return $query->where([
            ['start_at', '<=', $time->__toString()],
            ['end_at', '>', $time->__toString()],
        ]);
    }

    public function scopeStarted(Builder $query, Carbon $time)
    {
        return $query->where([
            ['start_at', '<', $time]
        ]);
    }

    public function scopeWeek(Builder $query, Carbon $time)
    {
        return $query->where([
            ['start_at', '>=', $time->startOfWeek()->toDateString()],
            ['end_at', '<=', $time->endOfWeek()->toDateString()]
        ]);
    }

    public function scopeUpcoming(Builder $query, Carbon $time)
    {
        return $query->where([
            ['start_at', '>', $time],
        ]);
    }
}
