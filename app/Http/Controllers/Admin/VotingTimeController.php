<?php

namespace App\Http\Controllers\Admin;

use App\Election;
use App\Http\Controllers\Controller;
use App\VotingTime;
use Illuminate\Http\Request;

class VotingTimeController extends Controller
{
    public function create(Election $election)
    {
        return view('votingtime.create', compact('election'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'election_id' => 'required|exists:elections,id',
            'start_at' => 'required|date_format:d.m.Y H:i',
            'end_at' => 'required|date_format:d.m.Y H:i',
        ]);

        Election::find($request->election_id)
            ->votingTimes()
            ->create($request->only(['start_at', 'end_at']));

        return redirect()->route('admin.election.show', $request->election_id);
    }

    public function edit(VotingTime $votingtime)
    {
        return view('votingtime.edit', compact('votingtime'));
    }

    public function update(VotingTime $votingtime, Request $request)
    {
        $this->validate($request, [
            'start_at' => 'required|date_format:d.m.Y H:i',
            'end_at' => 'required|date_format:d.m.Y H:i',
        ]);

        $votingtime->update($request->only(['start_at', 'end_at']));
        return redirect()->route('admin.election.show', $votingtime->election_id);
    }

    public function delete(VotingTime $votingtime)
    {
        $votingtime->delete();
        return redirect()->route('admin.election.show', $votingtime->election_id);
    }
}
