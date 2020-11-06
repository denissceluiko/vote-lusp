<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Commissioner extends Pivot
{
    public $incrementing = true;
    protected $fillable = ['role'];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function election() : BelongsTo
    {
        return $this->belongsTo(Election::class);
    }
}
