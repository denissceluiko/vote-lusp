@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $election->name }}</h1>
        <div class="row">
            <div class="col-12 col-lg-6">
                <h5>Vēlēšanu raksturlielumi</h5>
                <table class="table">
                    <tbody>
                    <tr>
                        <td>Izgatavotās vēlēšanu zīmes</td>
                        <td>{{ $election->ballots()->count() }}</td>
                    </tr>
                    <tr>
                        <td>Reģistrēti vēlētāji</td>
                        <td>{{ $election->voters()->count() }}</td>
                    </tr>
                    <tr>
                        <td>Izsniegtās vēlēšanu zīmes</td>
                        <td>{{ $election->ballots()->used()->count() }}</td>
                    </tr>
                    <tr>
                        <td>Veiktie balsojumi</td>
                        <td>{{ $election->ballots()->status('cast')->count() }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-lg-6">
                <h5>Balsošanas laiki</h5>
                <table class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Lídz</th>
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
        <div class="row">
            @foreach($election->parties as $party)
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Nr.{{ $party->number }} {{ $party->name }}</h5>
                            <dl class="row mb-0">
                                @foreach($party->members as $member)
                                    <dt class="col-2">{{ $loop->iteration }}.</dt>
                                    <dd class="col-10">{{ $member->name }} {{ $member->surname }}</dd>
                                @endforeach
                            </dl>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($election->inProgress())
        <a href="{{ route('election.registration', $election) }}" class="btn btn-primary btn-block">Balsot</a>
        @endif
    </div>
@endsection
