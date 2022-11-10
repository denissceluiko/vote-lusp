@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $election->name }}</h1>
        @if(session('ballotGeneration'))
            <div class="alert alert-info">{{ session('ballotGeneration') }}</div>
        @endif
        <div class="row">
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <div class="card">
                    <div class="card-header">Dati par vēlēšanu zīmēm</div>
                    <table class="table table-borderless mb-0">
                        <tbody>
                        <tr>
                            <td>Vēlētāji</td>
                            <td>{{ $election->faculty ? $election->faculty->students()->count() : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Izgatavotās vēlēšanu zīmes</td>
                            <td>{{ $election->ballots()->count() }}</td>
                        </tr>
                        <tr>
                            <td><a href="{{ route('admin.election.voters', $election) }}">Reģistrēti vēlētāji</a></td>
                            <td>{{ $election->voters()->count() }}</td>
                        </tr>
                        <tr>
                            <td>Izsniegtas vēlēšanu zīmes</td>
                            <td>{{ $election->ballots()->used()->count() }}</td>
                        </tr>
                        <tr>
                            <td>Neatvērtas vēlēšanu zīmes</td>
                            <td>{{ $election->ballots()->status('assigned')->count() }}</td>
                        </tr>
                        <tr>
                            <td>Atvērtas vēlēšanu zīmes</td>
                            <td>{{ $election->ballots()->status('opened')->count() }}</td>
                        </tr>
                        <tr>
                            <td>Veiktie balsojumi</td>
                            <td>{{ $election->ballots()->status('cast')->count() }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <div class="card">
                    <div class="card-header d-flex">
                        <div class="flex-grow-1">Balsošanas laiki</div>
                        <div class=""><a href="{{ route('admin.votingtime.create', $election) }}" class="btn btn-primary btn-sm">+</a></div>
                    </div>
                    <table class="table table-borderless">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Līdz</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($election->votingTimes as $time)
                            <tr>
                                <td>{{ $time->formatted('%from', 'd.m. H:i') }}</td>
                                <td>{{ $time->formatted('%to', 'd.m. H:i') }}</td>
                                <td><a href="{{ route('admin.votingtime.edit', $time) }}" class="">Rediģēt</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <div class="card">
                    <div class="card-body border-bottom">
                        <dl class="row">
                            <dt class="col-8">Mandātu skaits</dt>
                            <dd class="col-4">{{ $election->data('seats') }}</dd>
                        </dl>
                    </div>
                    <div class="card-body">
                        @if(auth()->user()->isAdmin())
                        <a class="btn btn-block btn-warning" href="{{ route('admin.election.edit', $election) }}">Rediģēt</a>
                        {{ Form::open(['route' => ['admin.election.generateBallots', $election]]) }}
                        {{ Form::submit('Ģenerēt biļetenus', ['class' => "form-control btn btn-block btn-info my-1"]) }}
                        {{ Form::close([]) }}
                        {{ Form::open(['route' => ['admin.election.voterList', $election]]) }}
                        {{ Form::submit('Lejupielādet velētāju sarakstu', ['class' => "form-control btn btn-block btn-info my-1"]) }}
                        {{ Form::close() }}
                        @endif
                        <a class="btn btn-block btn-warning" href="{{ route('admin.election.countingHelpers', $election) }}">Lejupielādēt palīglapas</a>
                        @if($election->isFinished())
                        <a class="btn btn-block btn-primary" href="{{ route('admin.election.protocol', $election) }}">Noslēgt vēlēšanas</a>
                        <a class="btn btn-block btn-success" href="{{ route('admin.protocol.create', $election) }}">Izveidot protokolu</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <th>Saraksts</th>
                    <th>Kandidāti</th>
                    </thead>
                    <tbody>
                    @foreach($election->parties as $party)
                        <tr>
                            <td><a href="{{ route('admin.party.show', $party) }}">{{ $party->name }}</a></td>
                            <td>{{ $party->candidates()->count() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
