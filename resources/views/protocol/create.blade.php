@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>{{ $faculty->name }} ({{ $faculty->abbreviation }})</h1>
                <div class="col-4">
                    {{ Form::open(['action' => ['ProtocolController@store', $faculty]]) }}
                    <div class="form-group">
                        {{ Form::label('member_count', 'SP biedru skaits') }}
                        {{ Form::text('member_count', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('voters_eligible', 'Balsstiesīgo skaits') }}
                        {{ Form::text('voters_eligible', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('voters_attended', 'Izsniegto zīmju skaits') }}
                        {{ Form::text('voters_attended', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('ballots_found', 'Urnā atrasto zīmju skaits') }}
                        {{ Form::text('ballots_found', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('ballots_void', 'Nederīgo zīmju skaits') }}
                        {{ Form::text('ballots_void', null, ['class' => 'form-control']) }}
                    </div>
                    {{ Form::submit('Izveidot protokolu', ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
