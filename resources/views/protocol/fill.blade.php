@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h1>{{ $protocol->faculty->abbreviation }} SP vēlēšanu protokols ({{ $protocol->id }})</h1>
            </div>
            {{ Form::open(['action' => ['ProtocolController@save', $protocol], 'class' => 'col-12']) }}
            <div class="row">
                @foreach($protocol->faculty->parties()->orderBy('number')->get() as $party)
                <div class="col-md-6 col-xl-4">
                    <div class="card mb-3">
                        <div class="card-header">{{ $party->number }}. {{ $party->name }}</div>
                        <div class="card-body">
                            <div class="form-group">
                                {{ Form::label("ballots_valid[{$party->id}]", 'Par sarakstu nodoto zīmju skaits') }}
                                {{ Form::text("ballots_valid[{$party->id}]", $party->ballots_valid, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label("ballots_changed[{$party->id}]", 'Par sarakstu nodoto grozīto zīmju skaits') }}
                                {{ Form::text("ballots_changed[{$party->id}]", $party->ballots_changed, ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label("ballots_unchanged[{$party->id}]", 'Par sarakstu nodoto negrozīto zīmju skaits') }}
                                {{ Form::text("ballots_unchanged[{$party->id}]", $party->ballots_unchanged, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            {{ Form::submit('Tālāk', ['class' => 'btn btn-primary']) }}
            {{ Form::close() }}
        </div>
    </div>
@endsection
