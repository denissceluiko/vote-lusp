<?php

namespace App\Http\Controllers;

use App\Imports\ElectionImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ElectionController extends Controller
{
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
