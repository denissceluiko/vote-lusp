<?php

namespace App\Http\Controllers;

use App\Election;
use App\Student;
use Illuminate\Http\Request;

class ElectionController extends Controller
{
    public function show(Election $election)
    {
        $election->load(['parties', 'ballots', 'votingTimes']);
        return view('election.show-guest', compact('election'));
    }

    public function registration(Election $election)
    {
        return view('election.registration', compact('election'));
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'election_id' => 'required|exists:elections,id',
            'student_id' => 'required|min:7'
        ]);

        $student = Student::bySID($request->get('student_id'))->first();
        $election = Election::find($request->get('election_id'));

        if (!$student) {
            return view('election.registred', compact('election'));
        }

        if (!$election->canVote($student)) {
            return view('election.registred', compact('election'));
        }

        if (!$election->inProgress()) {
            return view('election.registred', compact('election'));
        }

        if ($election->hasVoter($student)) {
            $voter = $election->getVoter($student);
            $election->attemptResending($voter);
        } else {
            $election->addVoter($student);
        }
        return view('election.registred', compact('election'));
    }
}
