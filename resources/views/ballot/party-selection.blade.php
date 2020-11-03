@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $ballot->election->name }}</h1>
        <h4>Kandidātu saraksti</h4>
        @if(!$ballot->election->isOpen())
            @include('snippets.voting-offline')
        @endif
        <div class="row">
            @foreach($parties as $party)
                <div class="col-12 col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Nr.{{ $party->number }} {{ $party->name }}</h5>
                            <dl class="row">
                            @foreach($party->members as $member)
                                <dt class="col-2">{{ $loop->iteration }}.</dt>
                                <dd class="col-10">{{ $member->name }} {{ $member->surname }}</dd>
                            @endforeach
                            </dl>
                            @if($ballot->election->isOpen())
                            {{ Form::open(['action' => ['BallotController@selectParty', $ballot]]) }}
                            {{ Form::hidden('password', request('password')) }}
                            {{ Form::hidden('party', $party->id) }}
                            {{ Form::submit('Izvēlēties šo sarakstu', ['class' => 'form-control btn btn-primary']) }}
                            {{ Form::close() }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @include('snippets.helpdesk')
    </div>
@endsection
