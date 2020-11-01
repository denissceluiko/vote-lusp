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
                    <div><a href="{{ route('faculty.index') }}">Faculties</a>: {{ App\Faculty::count() }}</div>
                    <div><a href="{{ route('election.index') }}">Elections</a>: {{ App\Election::count() }}</div>
                    <div><a href="{{ route('program.index') }}">Programs</a>: {{ App\Program::count() }}</div>
                    <div><a href="{{ route('student.index') }}">Students</a>: {{ App\Student::count() }}</div>
                    <div><a href="{{ route('party.index') }}">Parties</a>: {{ App\Party::count() }}</div>
                    <div>Candidates: {{ App\Candidate::count() }}</div>
                    <div><a href="{{ route('protocol.index') }}">Protocols</a>: {{ App\Protocol::count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Import faculties</div>
                <div class="card-body">
                    {{ Form::open(['action' => 'FacultyController@import', 'files' => true]) }}
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
                    {{ Form::open(['action' => 'ElectionController@import', 'files' => true]) }}
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
                    {{ Form::open(['action' => 'ProgramController@import', 'files' => true]) }}
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
                    {{ Form::open(['action' => 'StudentController@import', 'files' => true]) }}
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
                    {{ Form::open(['action' => 'CandidateController@import', 'files' => true]) }}
                    <div class="form-group">
                        {{ Form::file('candidate_file') }}
                    </div>
                    {{ Form::submit(null, ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
