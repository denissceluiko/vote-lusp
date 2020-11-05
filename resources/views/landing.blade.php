@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>2020. gada Latvijas Universitātes fakultāšu studentu pašpārvalžu vēlēšanas</h1>
        <p>Ņemot vērā to, ka šobrīd studijas notiek pārsvarā attālināti, pašpārvalžu vēlēšanas arī nolemts pārcelt uz tiešsaistes vidi.</p>
        <p>Lai saņemtu savu vēlēšanu zīmi, ievadi zemāk savu studenta apliecības numuru, nospied "Saņemt" un pārbaudi savu studenta e-pastu (<a href="https://www.lu.lv/epasts" target="_blank">lu.lv/epasts</a>). E-pastā būs tālākas instrukcijas balsojuma veikšanai.</p>
        <div class="card-columns">
            @foreach($elections as $election)
                <div class="card">
                    <div class="card-header">{{ $election->name }}</div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Balsošanas laiki</li>
                        @foreach($election->votingTimes as $time)
                            <li class="list-group-item">{{ $time->formatted('%from - %to', 'd.m. H:i') }}</li>
                        @endforeach
                    </ul>
                    <div class="card-footer bg-light">
                        <a class="btn btn-outline-dark btn-block" href="{{ route('election.show',$election) }}">Kandidāti</a>
                        @if($election->inProgress())
                            <a class="btn btn-primary btn-block" href="{{ route('election.registration', $election) }}">Balsot</a>
                        @endif
                    </div>
                </div>

            @endforeach
        </div>
        @include('snippets.helpdesk')
    </div>
@endsection
