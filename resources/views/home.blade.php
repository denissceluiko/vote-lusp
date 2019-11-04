@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
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
    </div>
</div>
@endsection
