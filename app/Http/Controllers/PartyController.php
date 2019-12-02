<?php

namespace App\Http\Controllers;

use App\Faculty;
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
    }

    public function index()
    {
        $parties = Party::orderBy('name')->get();
        return view('party.index', compact('parties'));
    }

    public function show(Party $party)
    {
        return view('party.show', compact('party'));
    }

    public function edit(Party $party)
    {
        $faculties = Faculty::orderBy('name')->get();
        return view('party.edit', compact('party', 'faculties'));
    }

    public function update(Request $request, Party $party)
    {
        $this->validate($request, [
            'name' => 'required',
            'number' => 'required|integer',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        $party->update($request->all());

        return redirect()->route('party.show', $party);
    }

    public function ballot(Party $party)
    {
        return response()->download(storage_path('app/'.$party->createBallot()));
    }
}
