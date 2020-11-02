@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3">
                <h1>{{ $ballot->election->name }}</h1>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lūdzu ievadi e-pastā norādīto paroli</h5>
                        {{ Form::open(['action' => ['BallotController@auth', $ballot]]) }}
                        <div class="form-group">
                            {{ Form::label('password', 'Parole', ['class' => '']) }}
                            {{ Form::password('password', ['class' => 'form-control mb-3', 'required']) }}
                            @error('password')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{ Form::submit('Saņemt', ['class' => 'form-control btn btn-primary']) }}
                        {{ Form::close() }}
                    </div>
                </div>
                @include('snippets.helpdesk')
            </div>
        </div>
    </div>
@endsection
