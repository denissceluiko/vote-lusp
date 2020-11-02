<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Election extends Model
{
    protected $fillable = ['name', 'name_short'];

    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function parties() : HasMany
    {
        return $this->hasMany(Party::class);
    }

    public function voters() : HasMany
    {
        return $this->hasMany(Voter::class);
    }

    public function ballots() : HasMany
    {
        return $this->hasMany(Ballot::class);
    }

    public function generateBallots()
    {
        $ballotCount = $this->ballots()->count();
        $ballotsRequired = $this->faculty->students()->count();

        for ($i=$ballotCount; $i<$ballotsRequired; $i++) {
            Ballot::generate($this);
        }
        return $ballotsRequired - $ballotCount;
    }

    public function hasVoter(Student $student)
    {
        return $this->voters()->student($student)->count() ? true : false;
    }
}
