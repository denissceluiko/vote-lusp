@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $election->name }}</h1>
        @include('snippets.voting-offline')
        @if(!$election->isFinished())
        <div class="alert alert-warning">Uz studenta, ja tāds eksistē un drīkst piedalīties šajās vēlēšanās, ar apliecības nr. {{ request('student_id') }} e-pastu, tika aizsūtīta instrukcija tālākajiem balsošanas soļiem.</div>
        @endif
        <p>Atceries, saņemt vēlēšanu zīmi un balsot vari tikai tavas FSP Vēlēšanu komisijas norādītajos balsošanas laikos! Ja vēlēšanas ir noslēgušās, vēlēšanu zīme ar instrukcijām netiks nosūtīta.</p>
        @include('snippets.helpdesk')
    </div>
@endsection
