<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Election extends Model
{
    protected $fillable = ['name', 'name_short'];

    protected $casts = [
        'data' => 'array',
    ];

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
        return $this->votingTimes()->open(Carbon::now())->exists();
    }

    public function hasStarted()
    {
        return $this->votingTimes()->started(Carbon::now())->exists();
    }

    public function isFinished()
    {
        return !($this->isOpen() || $this->votingTimes()->upcoming(Carbon::now())->exists());
    }

    public function inProgress()
    {
        return $this->hasStarted() && !$this->isFinished();
    }

    public function canVote(Student $student)
    {
        return $this->faculty_id == $student->program->faculty_id;
    }

    public function nextVotingTime()
    {
        return $this->votingTimes()->upcoming(Carbon::now())->first();
    }

    /**
     * Format: use %from and %to
     *
     * @param string $format
     * @param string $dateformat
     */
    public function nextVotingTimeFormatted(string $format, string $dateformat, string $pollsClosed)
    {
        $time = $this->nextVotingTime();
        if (!$time) return $pollsClosed;

        return $time->formatted($format, $dateformat);
    }

    public function scopeThisWeek(Builder $query)
    {
        return $query->whereHas('votingTimes', function (Builder $query) {
            $query->week(Carbon::now());
        });
    }
}
