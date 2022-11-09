@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>{{ $election->name }}</h1>
                <div class="col-4">
                    {{ Form::open(['route' => ['admin.protocol.store', $election]]) }}
                    <div class="form-group">
                        {{ Form::label('member_count', 'Ievēlamo biedru skaits') }}
                        {{ Form::text('member_count', $election->data['seats'] ?? '', ['class' => 'form-control', 'required']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('voters_eligible', 'Balsstiesīgo skaits') }}
                        {{ Form::text('voters_eligible', $election->faculty->students()->count() ?? 0, ['class' => 'form-control', 'required']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('voters_attended', 'Izsniegto zīmju skaits') }}
                        {{ Form::text('voters_attended', null, ['class' => 'form-control', 'required']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('ballots_found', 'Urnā atrasto zīmju skaits') }}
                        {{ Form::text('ballots_found', null, ['class' => 'form-control', 'required']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('ballots_void', 'Nederīgo zīmju skaits') }}
                        {{ Form::text('ballots_void', null, ['class' => 'form-control', 'required']) }}
                    </div>
                    {{ Form::submit('Izveidot protokolu', ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
