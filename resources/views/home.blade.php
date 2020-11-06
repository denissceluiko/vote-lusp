@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Statistics</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div><a href="{{ route('admin.faculty.index') }}">Faculties</a>: {{ App\Faculty::count() }}</div>
                    <div><a href="{{ route('admin.election.index') }}">Elections</a>: {{ App\Election::count() }}</div>
                    <div><a href="{{ route('admin.program.index') }}">Programs</a>: {{ App\Program::count() }}</div>
                    <div><a href="{{ route('admin.student.index') }}">Students</a>: {{ App\Student::count() }}</div>
                    <div><a href="{{ route('admin.party.index') }}">Parties</a>: {{ App\Party::count() }}</div>
                    <div>Candidates: {{ App\Candidate::count() }}</div>
                    <div><a href="{{ route('protocol.index') }}">Protocols</a>: {{ App\Protocol::count() }}</div>
                </div>
            </div>
        </div>
        @if(auth()->user()->isAdmin())
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Import faculties</div>
                <div class="card-body">
                    {{ Form::open(['action' => 'Admin\FacultyController@import', 'files' => true]) }}
                        <div class="form-group">
                            {{ Form::file('faculty_file') }}
                        </div>
                        {{ Form::submit(null, ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Import elections</div>
                <div class="card-body">
                    {{ Form::open(['action' => 'Admin\ElectionController@import', 'files' => true]) }}
                    <div class="form-group">
                        {{ Form::file('election_file') }}
                    </div>
                    {{ Form::submit(null, ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Import programs</div>
                <div class="card-body">
                    {{ Form::open(['action' => 'Admin\ProgramController@import', 'files' => true]) }}
                        <div class="form-group">
                            {{ Form::file('program_file') }}
                        </div>
                        {{ Form::submit(null, ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Import students</div>
                <div class="card-body">
                    {{ Form::open(['action' => 'Admin\StudentController@import', 'files' => true]) }}
                        <div class="form-group">
                            {{ Form::file('student_file') }}
                        </div>
                        {{ Form::submit(null, ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Import candidates</div>
                <div class="card-body">
                    {{ Form::open(['action' => 'Admin\CandidateController@import', 'files' => true]) }}
                    <div class="form-group">
                        {{ Form::file('candidate_file') }}
                    </div>
                    {{ Form::submit(null, ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
