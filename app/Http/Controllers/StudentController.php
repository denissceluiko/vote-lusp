<?php

namespace App\Http\Controllers;

use App\Imports\StudentImport;
use App\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
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
        $students = Student::with(['program'])->orderBy('name')->paginate(100);
        return view('student.index', compact('students'));
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'query' => 'required|min:3',
        ]);

        $students = Student::search($request->get('query'))->with(['program'])->orderBy('name')->paginate(100);
        return view('student.index', compact('students'));
    }

    /**
     * Imports students
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'student_file' => 'required|file'
        ]);

        $import = Excel::import(new StudentImport, $request->file('student_file'));
        return back();
    }


    public function show(Student $student)
    {
        return view('student.show', compact('student'));
    }
}
