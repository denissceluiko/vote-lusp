@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>2020. gada Latvijas Universitātes fakultāšu studentu pašpārvalžu vēlēšanas</h1>
        <p>Ņemot vērā to, ka šobrīd studijas notiek pārsvarā attālināti, pašpārvalžu vēlēšanas arī nolemts pārcelt uz tiešsaistes vidi.</p>
        <p>Lai saņemtu savu vēlēšanu zīmi, ievadi zemāk savu studenta apliecības numuru, nospied "Saņemt" un pārbaudi savu studenta e-pastu (<a href="https://www.lu.lv/epasts" target="_blank">lu.lv/epasts</a>). E-pastā būs tālākas instrukcijas balsojuma veikšanai.</p>
        <div class="row">
            @foreach($elections as $election)
            <div class="col-12 col-md-6 col-xl-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $election->name }}</h5>
                        <p class="card-text">Balsošanas laiki</p>
                        @foreach($election->votingTimes as $time)
                            <p>{{ $time->formatted('%from - %to', 'd.m. H:i') }}</p>
                        @endforeach
                        @if($election->inProgress())
                            <a class="card-link btn btn-primary btn-block" href="">Balsot</a>
                        @else
                            <a class="card-link btn btn-outline-dark btn-block" href="">Kanidāti</a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @include('snippets.helpdesk')
    </div>
@endsection
