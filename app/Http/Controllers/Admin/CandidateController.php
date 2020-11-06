<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CandidateImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CandidateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'candidate_file' => 'required|file',
        ]);

        Excel::import(new CandidateImport, $request->file('candidate_file'));
        return back();
    }
}
