@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3">
                <h1>2020. gada Latvijas Universitātes fakultāšu studentu pašpārvalžu vēlēšanas</h1>
                <div class="alert alert-warning">Uz studenta, ja tāds eksistē, ar apliecības nr. {{ request('student_id') }} e-pastu, tika aizsūtīta instrukcija tālākajiem balsošanas soļiem.</div>
                <p>Atceries, saņemt vēlēšanu zīmi un balsot vari tikai tavas FSP Vēlēšanu komisijas norādītajos balsošanas laikos! Ja vēlēšanas ir noslēgušās, vēlēšanu zīme ar instrukcijām netiks nosūtīta.</p>
                @include('snippets.helpdesk')
            </div>
        </div>
    </div>
@endsection
