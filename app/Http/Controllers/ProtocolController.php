<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Faculty;
use App\Party;
use App\Protocol;
use Illuminate\Http\Request;

class ProtocolController extends Controller
{
    public function create(Faculty $faculty)
    {
        return view('protocol.create', compact('faculty'));
    }

    public function store(Request $request, Faculty $faculty)
    {
        $this->validate($request, [
            'member_count' => 'required',
            'voters_eligible' => 'required',
            'voters_attended' => 'required',
            'ballots_found' => 'required',
            'ballots_void' => 'required',
        ]);

        $protocol = $faculty->protocols()->create($request->all());

        return redirect()->action('ProtocolController@fill', compact('protocol'));
    }

    public function fill(Protocol $protocol)
    {
        return view('protocol.fill', compact('protocol'));
    }

    public function save(Request $request, Protocol $protocol)
    {
        $this->validate($request, [
            'ballots_valid' => 'required',
            'ballots_changed' => 'required',
            'ballots_unchanged' => 'required',
        ]);

        foreach($request->get('ballots_valid') as $party_id => $count)
        {
            Party::find($party_id)->update(['ballots_valid' => $count]);
        }

        foreach($request->get('ballots_changed') as $party_id => $count)
        {
            Party::find($party_id)->update(['ballots_changed' => $count]);
        }

        foreach($request->get('ballots_unchanged') as $party_id => $count)
        {
            Party::find($party_id)->update(['ballots_unchanged' => $count]);
        }

        return redirect()->action('ProtocolController@fillCandidates', $protocol);
    }

    public function fillCandidates(Protocol $protocol)
    {
        return view('protocol.fillCandidates', compact('protocol'));
    }

    public function saveCandidates(Request $request, Protocol $protocol)
    {
        $this->validate($request, [
            'votes_for' => 'required',
            'votes_against' => 'required',
        ]);

        foreach($request->get('votes_for') as $candidate_id => $count)
        {
            Candidate::find($candidate_id)->update(['votes_for' => $count]);
        }

        foreach($request->get('votes_against') as $candidate_id => $count)
        {
            Candidate::find($candidate_id)->update(['votes_against' => $count]);
        }

        return redirect()->action('ProtocolController@results', $protocol);
    }

    public function results(Protocol $protocol)
    {
        return view('protocol.results', compact('protocol'));
    }
}
