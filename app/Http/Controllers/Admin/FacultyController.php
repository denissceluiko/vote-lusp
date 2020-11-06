<?php

namespace App\Http\Controllers\Admin;

use App\Faculty;
use App\Http\Controllers\Controller;
use App\Imports\FacultyImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FacultyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index', 'show']);
    }

    /**
     * Index page for faculties
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('faculty.index');
    }

    /**
     * Show page for faculties
     *
     * @param Faculty $faculty
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Faculty $faculty)
    {
        return view('faculty.show', compact('faculty'));
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
            'faculty_file' => 'required|file',
        ]);

        Excel::import(new FacultyImport, $request->file('faculty_file'));

        return back();
    }

    public function studentList(Faculty $faculty)
    {
        return response()->download(storage_path('app/'.$faculty->createStudentList()));
    }
}
