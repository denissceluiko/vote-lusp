<?php

namespace App\Http\Controllers;

use App\Party;

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

    public function ballot(Party $party)
    {
        return response()->download(storage_path('app/'.$party->createBallot()));
    }
}
