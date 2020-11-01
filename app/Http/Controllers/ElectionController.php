<?php

namespace App\Http\Controllers;

use App\Election;
use App\Imports\ElectionImport;
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
    }

    public function index()
    {
        $elections = Election::all();
        return view('election.index', compact('elections'));
    }

    public function show(Election $election)
    {
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
}
