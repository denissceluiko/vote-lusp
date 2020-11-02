<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ballot extends Model
{
    protected $fillable = ['slug', 'password'];

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
