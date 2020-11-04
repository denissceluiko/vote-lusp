@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>Saraksts "{{ $party->name }}"</h1>
                {{ Form::model($party, ['route' => ['admin.party.update', $party], 'method' => 'put']) }}
                <div class="form-group">
                    {{ Form::label('name', 'Nosaukums') }}
                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('number', 'Numurs') }}
                    {{ Form::text('number', null, ['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('election_id', 'Vēlēšanas') }}
                    <div class="form-text">{{ $party->election->name }}</div>
                </div>
                {{ Form::submit('Saglabāt', ['class' => 'btn btn-primary']) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
