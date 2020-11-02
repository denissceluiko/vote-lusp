@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3">
                <h1>2020. gada Latvijas Universitātes fakultāšu studentu pašpārvalžu vēlēšanas</h1>
                <p>Ņemot vērā to, ka šobrīd studijas notiek pārsvarā attālināti, pašpārvalžu vēlēšanas arī nolemts pārcelt uz tiešsaistes vidi.</p>
                <p>Lai saņemtu savu vēlēšanu zīmi, ievadi zemāk savu studenta apliecības numuru, nospied "Saņemt" un pārbaudi savu studenta e-pastu (<a href="https://www.lu.lv/epasts" target="_blank">lu.lv/epasts</a>). E-pastā būs tālākas instrukcijas balsojuma veikšanai.</p>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Saņem savu vēlēšanu zīmi</h5>
                        {{ Form::open(['action' => 'VoterController@register']) }}
                        <div class="form-group">
                            {{ Form::label('student_id', 'Studenta apliecības numurs', ['class' => '']) }}
                            {{ Form::text('student_id', null, ['class' => 'form-control mb-3', 'required']) }}
                            @if ($errors->has('student_id'))
                                <small class="form-text text-danger">{{ $errors->first('email') }}</small>
                            @endif
                        </div>
                        {{ Form::submit('Saņemt', ['class' => 'form-control btn btn-primary']) }}
                        {{ Form::close() }}
                    </div>
                </div>
                <p>Neskaidrību gadījumā droši raksti LU SP Vēlēšanu komisijai uz <a href="mailto:vk@lusp.lv">vk@lusp.lv</a> </p>
            </div>
        </div>
    </div>
@endsection
