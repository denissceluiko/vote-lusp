<?php

namespace App\Http\Controllers\Admin;

use App\Candidate;
use App\Election;
use App\Faculty;
use App\Http\Controllers\Controller;
use App\Party;
use App\Protocol;
use FormulaParser\FormulaParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProtocolController extends Controller
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
        return view('protocol.index');
    }

    public function create(Election $election)
    {
        return view('protocol.create', compact('election'));
    }

    public function store(Request $request, Election $election)
    {
        $this->validate($request, [
            'member_count' => 'required',
            'voters_eligible' => 'required',
            'voters_attended' => 'required',
            'ballots_found' => 'required',
            'ballots_void' => 'required',
        ]);

        $protocol = $election->protocols()->create($request->all());

        return redirect()->action('Admin\ProtocolController@fill', compact('protocol'));
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

        $parties = [];

        foreach($request->get('ballots_valid') as $party_id => $count)
        {
            $count = str_replace('0', '0.0', $count);
            $result = (new FormulaParser(str_replace(' ', '+', $count)))->getResult();
            $count = round($result[1]);
            $parties[$party_id] = ['ballots_valid' => $count];
        }

        foreach($request->get('ballots_changed') as $party_id => $count)
        {
            $count = str_replace('0', '0.0', $count);
            $result = (new FormulaParser(str_replace(' ', '+', $count)))->getResult();
            $count = round($result[1]);
            $parties[$party_id]['ballots_changed'] = $count;
        }

        foreach($request->get('ballots_unchanged') as $party_id => $count)
        {
            $count = str_replace('0', '0.0', $count);
            $result = (new FormulaParser(str_replace(' ', '+', $count)))->getResult();
            $count = round($result[1]);
            $parties[$party_id]['ballots_unchanged'] = $count;
        }

        $protocol->data = array_merge($protocol->data ?? [], ['parties' => $parties]);
        $protocol->save();

        return redirect()->action('Admin\ProtocolController@fillCandidates', $protocol);
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

        $candidates = [];

        foreach($request->get('votes_for') as $candidate_id => $count)
        {
            $count = str_replace('0', '0.0', $count);
            $result = (new FormulaParser(str_replace(' ', '+', $count)))->getResult();
            $count = round($result[1]);
            $candidates[$candidate_id]['votes_for'] = $count;
        }

        foreach($request->get('votes_against') as $candidate_id => $count)
        {
            $count = str_replace('0', '0.0', $count);
            $result = (new FormulaParser(str_replace(' ', '+', $count)))->getResult();
            $count = round($result[1]);
            $candidates[$candidate_id]['votes_against'] = $count;
        }

        foreach ($candidates as &$candidate) {
            $candidate['votes_sum'] = $candidate['votes_for'] - $candidate['votes_against'];
        }

        $protocol->data = array_merge($protocol->data ?? [], ['candidates' => $candidates]);
        $protocol->save();

        return redirect()->action('Admin\ProtocolController@results', $protocol);
    }

    public function results(Protocol $protocol)
    {
        return view('protocol.results', compact('protocol'));
    }

    public function export(Protocol $protocol)
    {
        return Storage::disk('protocols')->download($protocol->export(), $protocol->election->name.' protokols.docx');
    }
}
