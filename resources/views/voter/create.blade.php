@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3">
                <h1>2020. gada Latvijas Universitātes fakultāšu studentu pašpārvalžu vēlēšanas</h1>
                <p>Ņemot vērā to, ka šobrīd studijas notiek pārsvarā attālināti, pašpārvalžu vēlēšanas arī nolemts pārcelt uz tiešsaistes vidi.</p>
                <p>Lai saņemtu savu vēlēšanu zīmi, ievadi zemāk savu studenta apliecības numuru, nospied "Saņemt" un pārbaudi savu studenta e-pastu (<a href="https://www.lu.lv/epasts" target="_blank">lu.lv/epasts</a>). E-pastā būs tālākas instrukcijas balsojuma veikšanai.</p>
                <h4 class="my-2 text-danger">Šobrīd ir zināma problēma saņemt e-pastus tiem, kam stud. apl. nr. atšķiras no LUIS logina, šī problēma tiek risināta.</h4>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Saņem savu vēlēšanu zīmi</h5>
                        {{ Form::open(['action' => 'VoterController@register']) }}
                        <div class="form-group">
                            {{ Form::label('student_id', 'Studenta apliecības numurs', ['class' => '']) }}
                            {{ Form::text('student_id', null, ['class' => 'form-control mb-3', 'required']) }}
                            @error('student_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        {{ Form::submit('Saņemt', ['class' => 'form-control btn btn-primary']) }}
                        {{ Form::close() }}
                    </div>
                </div>
                @include('snippets.helpdesk')
            </div>
        </div>
    </div>
@endsection
