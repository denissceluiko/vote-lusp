@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $election->name }}</h1>
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
                    <div class="card-header">Balsošanas laiki</div>
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
                        @endif
                        @if($election->isFinished())
                        <a class="btn btn-block btn-primary" href="{{ route('admin.election.protocol', $election) }}">Noslēgt vēlēšanas</a>
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
