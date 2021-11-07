<?php

namespace App\Http\Controllers\Admin;

use App\Candidate;
use App\Election;
use App\Http\Controllers\Controller;
use App\Imports\ElectionImport;
use App\Jobs\GenerateBallots;
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
        $this->middleware('admin')->only(['import']);
    }

    public function index()
    {
        $elections = Election::all();
        return view('election.index', compact('elections'));
    }

    public function show(Election $election)
    {
        if (!$election->canSee()) return back();
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

    public function edit(Election $election)
    {
        if (!auth()->user()->isAdmin()) return back();
        return view('election.edit', compact('election'));
    }

    public function update(Request $request, Election $election)
    {
        if (!auth()->user()->isAdmin()) return back();
        $this->validate($request, [
            'name' => 'required',
            'name_short' => 'required',
            'seats' => 'required|int',
        ]);

        $election->fill($request->all());
        $election->data('seats', $request->seats);

        $election->save();
        return redirect()->route('admin.election.show', compact('election'));
    }

    public function protocol(Election $election)
    {
        if (!$election->isFinished()) return back();
        if (!$election->canSee()) return back();

        $election->load(['ballots', 'parties.candidates.student']);

        $ballotBlob = $election->ballots()->used()->get()->pluck('vote');
        $ballotData = collect();

        $ballotBlob->each(function ($vote) use ($ballotData) {
            $ballotData->push(json_decode($vote));
        });

        $election->parties->each(function (Party $party) use ($ballotData) {
            $party->votes = $ballotData->where('party_id', $party->id)->count();

            $party->candidates = $party->candidates->each(function (Candidate $candidate) use ($ballotData, $party) {
                $candidate->votesFor = $ballotData->where('candidates.'.$candidate->student_id, '=', '1')->count();
                $candidate->votesAgainst = $ballotData->where('candidates.'.$candidate->student_id, '=', '-1')->count();
                $candidate->votesTotal = $candidate->votesFor - $candidate->votesAgainst;
            })->sortByDesc('votesTotal')->values()->each(function (Candidate $candidate, $key) use ($party) {
                $candidate->division = $party->votes / (2*$key+1);
            });
        });

        return view('election.protocol', compact('election'));
    }

    public function generateBallots(Election $election)
    {
        GenerateBallots::dispatch($election);
        return back()->with(['ballotGeneration' => 'Sāka ģenerēt vēlēšanu zīmes']);
    }
}
