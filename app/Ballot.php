<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Ballot extends Model
{
    protected $fillable = ['slug'];

    public static $statuses = [
        'generated' => 0,
        'assigned' => 1,
        'opened' => 2,
        'cast' => 3,
    ];

    public function election() : BelongsTo
    {
        return $this->belongsTo(Election::class);
    }
    public function voter() : BelongsTo
    {
        return $this->belongsTo(Voter::class);
    }

    public function scopeUnused(Builder $query)
    {
        return $query->where('status', self::$statuses['generated']);
    }

    public function assign(Voter $voter)
    {
        $this->voter()->associate($voter);
        $this->password = Crypt::encryptString(Str::random(32));
        $this->status = self::$statuses['assigned'];
        $this->save();
        return $this;
    }

    public function open()
    {
        $this->voter()->dissociate();
        $this->status = self::$statuses['opened'];
        $this->save();
        return $this;
    }

    public static function generate(Election $election)
    {
        $election->ballots()->create([
            'slug' => Str::random(32),
        ]);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
