@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $election->name }}</h1>
        @if($election->isFinished())
            <div class="alert alert-info">Balsošana {{ $election->name }} ir noslēgusies</div>
        @elseif(!$election->isOpen())
            <div class="alert alert-danger">Šobrīd var tikai apskatīt <a href="{{ route('election.show', $election) }}">kandidātu sarakstus</a>. {{ $election->nextVotingTimeFormatted('Tuvākais balsošanas laiks no %from lídz %to', 'd.m.Y. H:i', 'Balsošana ir noslēgusies.') }}</div>
        @endif
        @if(!$election->isFinished())
        <div class="alert alert-warning">Uz studenta, ja tāds eksistē un drīkst piedalīties šajās vēlēšanās, ar apliecības nr. {{ request('student_id') }} e-pastu, tika aizsūtīta instrukcija tālākajiem balsošanas soļiem.</div>
        @endif
        <p>Atceries, saņemt vēlēšanu zīmi un balsot vari tikai tavas FSP Vēlēšanu komisijas norādītajos balsošanas laikos! Ja vēlēšanas ir noslēgušās, vēlēšanu zīme ar instrukcijām netiks nosūtīta.</p>
        @include('snippets.helpdesk')
    </div>
@endsection
