<?php

namespace App\Http\Controllers;

use App\Party;

class PartyController extends Controller
{
    public function index()
    {
        return view('party.index');
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
