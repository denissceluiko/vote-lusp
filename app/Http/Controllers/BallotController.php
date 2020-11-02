<?php

namespace App\Http\Controllers;

use App\Ballot;
use App\Party;
use Illuminate\Http\Request;

class BallotController extends Controller
{
    public function show(Ballot $ballot)
    {
        if (!$ballot->isViewable()) return abort(404);

        if ($ballot->isCast())
            return view('ballot.cast', compact('ballot'));

        return view('ballot.show', compact('ballot'));

    }

    public function auth(Request $request, Ballot $ballot)
    {
        if ($ballot->isCast()) return redirect()->action('BallotController@show', $ballot);

        $this->validate($request, [
            'password' => 'required'
        ]);

        if (!$ballot->verifyPassword($request->get('password'))) {
            return back()->withErrors(['password' => 'Nepareiza parole']);
        }

        $ballot->open();

        $parties = $ballot->election->parties()->orderBy('number')->with(['members'])->get();

        return view('ballot.party-selection', compact('ballot', 'parties'));
    }

    public function selectParty(Request $request, Ballot $ballot)
    {
        if ($ballot->isCast()) return redirect()->action('BallotController@show', $ballot);

        $this->validate($request, [
            'password' => 'required',
            'party' => 'required|exists:parties,id'
        ]);

        $party = Party::find($request->get('party'));

        if (!$ballot->verifyPassword($request->get('password')) || !$ballot->verifyParty($party)) {
            return redirect()->action('BallotController@show', $ballot);
        }


        return view('ballot.candidate-selection', compact('ballot', 'party'));
    }

    public function vote(Request $request, Ballot $ballot)
    {
        if ($ballot->isCast()) return redirect()->action('BallotController@show', $ballot);

        $this->validate($request, [
            'password' => 'required',
            'party' => 'required|exists:parties,id',
        ]);

        $party = Party::find($request->get('party'));

        if (!$ballot->verifyPassword($request->get('password')) ||
            !$ballot->verifyParty($party) ||
            !$ballot->verifyCandidates($request->get('candidates'))
        ) {
            return redirect()->action('BallotController@show', $ballot);
        }

        $vote = [
            'party_id' => $party->id,
            'candidates' => $request->get('candidates'),
        ];

        $ballot->vote($vote);

        return view('ballot.cast', compact('ballot'));
    }

}
