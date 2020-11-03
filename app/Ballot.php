<?php

namespace App;

use App\Notifications\BallotSent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
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

    public function scopeUsed(Builder $query)
    {
        return $query->where('status', '!=', self::$statuses['generated']);
    }

    public function scopeStatus(Builder $query, string $status)
    {
        return $query->where('status', self::$statuses[$status]);
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

    public function vote($vote)
    {
        $this->voter()->dissociate();
        $this->status = self::$statuses['cast'];
        $this->vote = json_encode($vote);
        $this->save();
        return $this;
    }

    public function send()
    {
        if (!$this->isAssigned()) return false;

        Notification::route('mail', $this->formatRecipient())
            ->notify(new BallotSent($this));

        Log::info("Ballot sent to {$this->voter->student->sid}");
        return true;
    }

    protected function formatRecipient()
    {
        return $this->voter->student->sid.'@students.lu.lv';
    }


    public static function generate(Election $election)
    {
        $election->ballots()->create([
            'slug' => Str::random(32),
        ]);
    }

    public function verifyPassword($password)
    {
        return $password === Crypt::decryptString($this->password);
    }

    public function verifyParty(Party $party)
    {
        return $party->election->id === $this->election->id;
    }

    public function verifyCandidates($candidates)
    {
        if (empty($candidates)) return true;
        if (!is_array($candidates)) return false;

        foreach ($candidates as $candidate => $v) {
            if (!$this->election->candidates()->where('student_id', $candidate)->exists()) return false;
        }

        return true;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function isViewable()
    {
        return $this->status != self::$statuses['generated'];
    }

    public function isAssigned()
    {
        return $this->status == self::$statuses['assigned'];
    }

    public function isCast()
    {
        return $this->status == self::$statuses['cast'];
    }
}
