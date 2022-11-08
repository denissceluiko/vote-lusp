<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

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

        $candidates = $candidates->sortByDesc('votes_sum');

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

    public function export()
    {

        $doc = new TemplateProcessor(resource_path('templates/Protocol-template.docx'));

        // Basic info
        $doc->setValue('date', date('d.m.Y.'));
        $doc->setValue('voters_eligible', $this->voters_eligible);
        $doc->setValue('voters_attended', $this->voters_attended);
        $doc->setValue('ballot_count', $this->ballot_count);
        $doc->setValue('ballots_found', $this->ballots_found);
        $doc->setValue('ballots_ok', $this->ballots_found - $this->ballots_void);
        $doc->setValue('ballots_void', $this->ballots_void);

        // Votes per party
        $doc->cloneRow('party_ballots_name', $this->election->parties->count());

        $i = 1;
        foreach ($this->election->parties as $party)
        {
            $doc->setValues([
                "party_ballots_name#$i" => $party->name.'.',
                "party_ballots_valid#$i" => $this->data['parties'][$party->id]['ballots_valid'],
                "party_ballots_changed#$i" => $this->data['parties'][$party->id]['ballots_changed'],
                "party_ballots_unchanged#$i" => $this->data['parties'][$party->id]['ballots_unchanged'],
            ]);

            $i++;
        }


        // Mandate divisions
        $doc->cloneRow('dno', min($this->member_count, $this->election->candidates()->count()));

        $i = 1;
        foreach ($this->getDistribution() as $position) {
            $doc->setValues([
                "dno#$i" => $i,
                "mandate_divisions_party#$i" => $position['candidate']->party->name,
                "mandate_divisions_candidate#$i" => $position['candidate']->student->name .' '. $position['candidate']->student->surname,
                "mandate_divisions_div#$i" => $position['div'],
            ]);


            $i++;
        }

        $i = 1;

        // Party divisions

        $doc->cloneBlock('party_block', $this->election->parties->count(), true, true);

        $i = 1;

        foreach ($this->election->parties as $party) {
            $doc->setValue("party_divisions_no#$i", $i);
            $doc->setValue("party_divisions_name#$i", $party->name);

            $doc->cloneRow("pdno#$i", $party->candidates->count());

            $j = 1;
            foreach($party->candidates as $candidate) {
                $doc->setValue("pdno#$i#$j", $j);
                $doc->setValue("pd_candidate#$i#$j", $candidate->student->name .' '. $candidate->student->surname);
                $doc->setValue("pdc_plus#$i#$j", $this->data['candidates'][$candidate->id]['votes_for']);
                $doc->setValue("pdc_against#$i#$j", $this->data['candidates'][$candidate->id]['votes_against']);
                $doc->setValue("pdc_sum#$i#$j", $this->data['candidates'][$candidate->id]['votes_sum']);
                $j++;
            }

            $i++;
        }

        // Results
        $doc->cloneBlock('e_block', min($this->member_count, $this->election->candidates()->count()), true, true);

        $i = 1;
        foreach($this->getDistribution() as $elected) {
            $doc->setValue("elected_name#$i", $elected['candidate']->student->name .' '. $elected['candidate']->student->surname);
            $i++;
        }

        $path = Storage::disk('protocols')->putFileAs(
            '/',
            new File($doc->save()),
            sha1($this->election->name.$this->id).'.docx',
            'public'
        );

        return $path;
    }
}
