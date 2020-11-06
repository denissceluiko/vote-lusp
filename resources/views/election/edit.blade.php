@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>Saraksts "{{ $election->name }}"</h1>
                {{ Form::model($election, ['route' => ['admin.election.update', $election], 'method' => 'put']) }}
                <div class="form-group">
                    {{ Form::label('name', 'Nosaukums') }}
                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('name_short', 'Īsais nosaukums') }}
                    {{ Form::text('name_short', null, ['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('seats', 'Mandātu skaits') }}
                    {{ Form::text('seats', null, ['class' => 'form-control']) }}
                </div>
                {{ Form::submit('Saglabāt', ['class' => 'btn btn-primary']) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
