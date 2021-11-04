<?php

namespace App\Http\Controllers\Admin;

use App\Election;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VotingTimeController extends Controller
{
    public function create(Election $election)
    {
        return view('votingtime.create', compact('election'));
    }

    public function store(Election $election, Request $request)
    {
        $this->validate($request, [
            'start_at' => 'required|date_format:d.m.Y H:i',
            'end_at' => 'required|date_format:d.m.Y H:i',
        ]);

        $election->votingTimes()->create($request->only(['start_at', 'end_at']));
        return back();
    }
}
