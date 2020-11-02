<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VoterController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'student_id' => 'required|min:7'
        ]);

        $student = Student::bySID($request->get('student_id'))->first();

        if (!$student) {
            return view('voter.registred');
        }

        $election = $student->program->faculty->elections()->first();
        if (!$election) {
            Log::warning("Could not find election for {$student->sid}");
            return view('voter.registred');
        }

        if (!$election->isOpen()) {
            return view('voter.registred'); // TODO: give a proper error message
        }

        if ($election->hasVoter($student)) {
            $voter = $election->getVoter($student);
            $election->attemptResending($voter);
        } else {
            $election->addVoter($student);
        }
        return view('voter.registred');
    }

    public function create()
    {
        return view('voter.create');
    }
}
