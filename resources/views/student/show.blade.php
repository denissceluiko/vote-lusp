@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>{{ $student->name }} {{ $student->surname }}</h1>
                <h5 class="text-muted">{{ $student->sid }}</h5>
                <div class="col-12 mt-4">
                    <dl class="row">
                        <dt class="col-6">Fakultāte</dt>
                        <dd class="col-6"><a href="{{ route('faculty.show', $student->faculty) }}">{{ $student->faculty->name }}</a></dd>
                        <dt class="col-6">Prorgramma</dt>
                        <dd class="col-6"><a href="{{ route('program.show', $student->program) }}">{{ $student->program->name }}</a></dd>
                        <dt class="col-6">Statuss</dt>
                        <dd class="col-6">{{ $student->status }}</dd>
                        @if($student->email)
                        <dt class="col-6">E-pasts</dt>
                        <dd class="col-6">{{ $student->email }}</dd>
                        @endif
                        @if($student->phone)
                        <dt class="col-6">Telefons</dt>
                        <dd class="col-6">{{ $student->phone }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
