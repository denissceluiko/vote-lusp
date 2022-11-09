<?php

namespace App\Http\Controllers\Admin;

use App\Election;
use App\Http\Controllers\Controller;
use App\Party;
use Illuminate\Http\Request;

class PartyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }

    public function index()
    {
        $parties = Party::orderBy('name')->get();
        return view('party.index', compact('parties'));
    }

    public function show(Party $party)
    {
        if (!$party->canSee()) return back();
        return view('party.show', compact('party'));
    }

    public function edit(Party $party)
    {
        $elections = Election::orderBy('name')->get();
        return view('party.edit', compact('party', 'elections'));
    }

    public function update(Request $request, Party $party)
    {
        $this->validate($request, [
            'name' => 'required',
            'number' => 'required|integer',
            'election_id' => 'required|exists:elections,id',
        ]);

        $party->update($request->all());

        return redirect()->route('admin.party.show', $party);
    }

    public function ballot(Party $party)
    {
        return response()->download(storage_path('app/'.$party->createBallot()));
    }

    public function countingHelper(Party $party)
    {
        return response()->download(storage_path('app/'.$party->createCountingHelper()));
    }
}
