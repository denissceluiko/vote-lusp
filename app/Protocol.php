<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Protocol extends Model
{
    protected $guarded = [];
    protected $distribution = [];

    public function faculty() : BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    protected function getDivisions(Party $party)
    {
        $result = [];

        $n = 1;
        foreach($party->candidates()->byVotes()->get() as $candidate)
        {
            $result[] = [
                'candidate' => $candidate,
                'div' => $party->ballots_valid / $n,
            ];
            $n += 2;
        }

        return $result;
    }

    protected function sortDistributions()
    {
        uasort($this->distribution, function($a, $b) {
            return $a['div'] < $b['div'];
        });
    }

    /**
     * @return array
     */
    public function getDistribution(): array
    {
        if (!empty($this->distribution))
            return $this->distribution;


        foreach ($this->faculty->parties as $party)
        {
            $this->distribution = array_merge($this->distribution, $this->getDivisions($party));
        }

        $this->sortDistributions();

        return $this->distribution;
    }

    public function getMandates(): array
    {
        $mandates = [];

        foreach ($this->getDistribution() as $mandate)
        {
            if (!isset($mandates[$mandate['candidate']->party_id])) {
                $mandates[$mandate['candidate']->party_id] = 0;
            }
            $mandates[$mandate['candidate']->party_id]++;
        }

        return $mandates;
    }
}
