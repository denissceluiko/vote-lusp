<?php

namespace App\Http\Controllers\Admin;

use App\Candidate;
use App\Election;
use App\Http\Controllers\Controller;
use App\Imports\ElectionImport;
use App\Party;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ElectionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $elections = Election::all();
        return view('election.index', compact('elections'));
    }

    public function show(Election $election)
    {
        return view('election.show', compact('election'));
    }

    /**
     * Imports faculties
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'election_file' => 'required|file',
        ]);

        Excel::import(new ElectionImport, $request->file('election_file'));

        return back();
    }

    public function voters(Election $election)
    {
        $election->load(['voters', 'voters.student']);
        return view('election.voters', compact('election'));
    }


    public function protocol(Election $election)
    {
        if (!$election->isFinished()) back();

        $election->load(['ballots', 'parties.candidates.student']);

        $parties = $election->parties;

        $ballotBlob = $election->ballots()->used()->get()->pluck('vote');
        $ballotData = collect();

        $ballotBlob->each(function ($vote) use ($ballotData) {
            $ballotData->push(json_decode($vote));
        });

        $parties->each(function (Party $party) use ($ballotData) {
            $party->votes = $ballotData->where('party_id', $party->id)->count();


            $party->candidates->each(function (Candidate $candidate) use ($ballotData) {
                $candidate->votesFor = $ballotData->where('candidates.'.$candidate->student_id, '=', '1')->count();
                $candidate->votesAgainst = $ballotData->where('candidates.'.$candidate->student_id, '=', '-1')->count();
            });
        });

        return view('election.protocol', compact('election', 'parties'));
    }
}
