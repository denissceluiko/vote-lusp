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
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                    <th>Saraksts</th>
                    <th>Kandidāti</th>
                    <th>Balsis par sarakstu</th>
                    </thead>
                    <tbody>
                    @foreach($election->parties as $party)
                        <tr>
                            <td><a href="{{ route('admin.party.show', $party) }}">{{ $party->name }}</a></td>
                            <td>{{ $party->candidates()->count() }}</td>
                            <td>{{ $party->votes }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            @foreach($election->parties as $party)
            <div class="col-12 col-md-6">
                <h5>Nr. {{ $party->number }} {{ $party->name }}</h5>
                <table class="table">
                    <thead>
                        <th>Kandidāts</th>
                        <th>Par</th>
                        <th>Pret</th>
                        <th>Kopā</th>
                    </thead>
                    <tbody>
                        @foreach($party->candidates as $candidate)
                        <tr>
                            <td><a href="{{ route('admin.student.show', $candidate->student) }}">{{ $candidate->student->fullname }}</a> ({{ $candidate->student_id }})</td>
                            <td>{{ $candidate->votesFor }}</td>
                            <td>{{ $candidate->votesAgainst }}</td>
                            <td>{{ $candidate->votesFor - $candidate->votesAgainst }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        </div>
        <div class="row">
            <h3>Dalījumi</h3>
            <table class="table">
                <thead></thead>
                <tbody>
                @foreach($election->parties->pluck('candidates')->flatten()->sortByDesc('division') as $candidate)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a href="{{ route('admin.student.show', $candidate->student) }}">{{ $candidate->student->fullname }}</a> ({{ $candidate->student_id }})</td>
                        <td>{{ $candidate->party->name }}</td>
                        <td>{{ $candidate->division }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <th>Vēlēšanu zīme</th>
                    <th>Statuss</th>
                    <th>Balss</th>
                    </thead>
                    <tbody>
                    @foreach($election->ballots as $ballot)
                        <tr>
                            <td>{{ $ballot->slug }}</td>
                            <td>{{ $ballot->status }}</td>
                            <td>{{ $ballot->vote }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
