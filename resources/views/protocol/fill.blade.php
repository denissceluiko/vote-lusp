@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h1>{{ $protocol->election->name }} vēlēšanu protokols ({{ $protocol->id }})</h1>
                <p>Ailītēs droši raksti gan summas cik kopā saskaitīts, gan aritmētiskas izteiksmes, piem., (3+4+4+5), ja rezultātus sauc vairāki cilvēki. Tā kā rakstīt plusus ir čakarīgi, to pašu var uzraksīt tos aizvietojot ar tukšumsimboliem, piem., (3 4 4 5). Sistēma pati izrēķinās summu.</p>
            </div>
            {{ Form::open(['action' => ['Admin\ProtocolController@save', $protocol], 'class' => 'col-12']) }}
            <div class="row">
                @foreach($protocol->election->parties()->orderBy('number')->get() as $party)
                <div class="col-md-6 col-xl-4">
                    <div class="card mb-3">
                        <div class="card-header">{{ $party->number }}. {{ $party->name }}</div>
                        <div class="card-body">
                            <div class="form-group">
                                {{ Form::label("ballots_valid[{$party->id}]", 'Par sarakstu nodoto zīmju skaits') }}
                                {{ Form::text("ballots_valid[{$party->id}]", $protocol->data['parties'][$party->id]['ballots_valid'] ?? '', ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label("ballots_changed[{$party->id}]", 'Par sarakstu nodoto grozīto zīmju skaits') }}
                                {{ Form::text("ballots_changed[{$party->id}]", $protocol->data['parties'][$party->id]['ballots_changed'] ?? '', ['class' => 'form-control']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label("ballots_unchanged[{$party->id}]", 'Par sarakstu nodoto negrozīto zīmju skaits') }}
                                {{ Form::text("ballots_unchanged[{$party->id}]", $protocol->data['parties'][$party->id]['ballots_unchanged'] ?? '', ['class' => 'form-control']) }}
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
