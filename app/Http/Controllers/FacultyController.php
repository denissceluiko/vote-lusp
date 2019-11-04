<?php

namespace App\Http\Controllers;

use App\Imports\FacultyImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FacultyController extends Controller
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
            'faculty_file' => 'required|file',
        ]);

        Excel::import(new FacultyImport, $request->file('faculty_file'));

        return back();
    }
}
