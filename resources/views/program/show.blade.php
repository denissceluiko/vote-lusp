@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>{{ $program->name }}</h1>
                <h6><a href="{{ route('faculty.show', $program->faculty) }}">{{ $program->faculty->name }}</a></h6>
                <div class="col-12">
                    <a href="{{ route('program.studentList', $program) }}" class="btn btn-secondary">Generate student list</a>
                </div>
                <div class="col-md-6 mt-2">
                    <dl class="row">
                        <dt class="col-6">LRI kods</dt>
                        <dd class="col-6">{{ $program->lri }}</dd>
                        <dt class="col-6">LUIS kods</dt>
                        <dd class="col-6">{{ $program->code }}</dd>
                        <dt class="col-6">Studenti</dt>
                        <dd class="col-6">{{ $program->students()->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
