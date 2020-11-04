@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $election->name }}</h1>
        <p>Lai saņemtu savu vēlēšanu zīmi, ievadi zemāk savu studenta apliecības numuru, nospied "Saņemt" un pārbaudi savu studenta e-pastu (<a href="https://www.lu.lv/epasts" target="_blank">lu.lv/epasts</a>). E-pastā būs tālākas instrukcijas balsojuma veikšanai.</p>
        @if(!$election->isOpen())
        <div class="alert alert-danger">Šobrīd var tikai apskatīt <a href="{{ route('election.show', $election) }}">kandidātu sarakstus</a>. {{ $election->nextVotingTimeFormatted('Tuvākais balsošanas laiks no %from lídz %to', 'd.m.Y. H:i', 'Balsošana ir noslēgusies.') }}</div>
        @else
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Saņem savu vēlēšanu zīmi</h5>
                        {{ Form::open(['action' => ['ElectionController@register', $election]]) }}
                        {{ Form::hidden('election_id', $election->id) }}
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
            </div>
        </div>
        @endif
        @include('snippets.helpdesk')
    </div>
@endsection
