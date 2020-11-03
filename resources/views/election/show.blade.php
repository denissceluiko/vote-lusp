@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $election->name }}</h1>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Param</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Ballots</td>
                        <td>{{ $election->ballots()->count() }}</td>
                    </tr>
                    <tr>
                        <td>Voters registred</td>
                        <td>{{ $election->voters()->count() }}</td>
                    </tr>
                    <tr>
                        <td>Ballots used</td>
                        <td>{{ $election->ballots()->used()->count() }}</td>
                    </tr>
                    <tr>
                        <td>Ballots unopened</td>
                        <td>{{ $election->ballots()->status('assigned')->count() }}</td>
                    </tr>
                    <tr>
                        <td>Ballots opened</td>
                        <td>{{ $election->ballots()->status('opened')->count() }}</td>
                    </tr>
                    <tr>
                        <td>Ballots cast</td>
                        <td>{{ $election->ballots()->status('cast')->count() }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <th>Party</th>
                    <th>Candidates</th>
                    </thead>
                    <tbody>
                    @foreach($election->parties as $party)
                        <tr>
                            <td><a href="{{ route('party.show', $party) }}">{{ $party->name }}</a></td>
                            <td>{{ $party->candidates()->count() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
