<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    public function candidates() : HasManyThrough
    {
        return $this->hasManyThrough(Candidate::class, Party::class);
    }

    public function voters() : HasMany
    {
        return $this->hasMany(Voter::class);
    }

    public function ballots() : HasMany
    {
        return $this->hasMany(Ballot::class);
    }

    public function votingTimes() : HasMany
    {
        return $this->hasMany(VotingTime::class);
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
        return $this->voters()->student($student)->exists();
    }

    public function getVoter(Student $student) : Voter
    {
        return $this->voters()->student($student)->first();
    }

    public function addVoter(Student $student)
    {
        if ($this->hasVoter($student)) return false;

        $ballot = $this->getUnusedBallot();

        $voter = $this->voters()->create([
            'student_id' => $student->id,
            'election_id' => $this->id,
        ]);

        $ballot->assign($voter);
        $ballot->send();
    }

    public function attemptResending(Voter $voter)
    {
        if (!$voter->ballot) return false;
        return $voter->ballot->send();
    }

    private function getUnusedBallot() : Ballot
    {
        return $this->ballots()->unused()->first();
    }

    public function isOpen()
    {
        return $this->votingTimes(Carbon::now())->exists();
    }
}
