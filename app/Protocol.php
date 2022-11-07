<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Protocol extends Model
{
    protected $guarded = [];
    protected $distribution = [];

    protected $casts = [
        'data' => 'array',
    ];

    public function election() : BelongsTo
    {
        return $this->belongsTo(Election::class);
    }

    protected function getDivisions(Party $party)
    {
        $result = [];

        $candidates = $party->candidates()->get();

        foreach ($candidates as $candidate) {
            $candidate['votes_sum'] = $this->data['candidates'][$candidate->id]['votes_sum'];
        }

        $candidates->sortByDesc(['votes_sum', 'id']);

        $n = 1;
        foreach ($candidates as $candidate)
        {
            $result[] = [
                'candidate' => $candidate,
                'div' => $this->data['parties'][$party->id]['ballots_valid'] / $n,
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


        foreach ($this->data['parties'] as $party_id => $party)
        {
            $this->distribution = array_merge($this->distribution, $this->getDivisions(Party::find($party_id)));
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
