@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3">
                <h1>2020. gada Latvijas Universitātes fakultāšu studentu pašpārvalžu vēlēšanas</h1>
                <div class="alert alert-warning">Ja eksistē e-pasts {{ request('student_id') }}@students.lu.lv, uz to tika aizsūtīta instrukcija tālākajiem balsošanas soļiem.</div>
                <p>Atceries, saņemt vēlēšanu zīmi un balsot vari tikai tavas FS Vēlēšanu komisijas norādītajos balsošanas laikos!</p>
                @include('snippets.helpdesk')
            </div>
        </div>
    </div>
@endsection
