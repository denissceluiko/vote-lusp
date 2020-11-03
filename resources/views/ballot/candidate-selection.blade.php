@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $ballot->election->name }}</h1>
        <h4>Kandidātu saraksts Nr. {{ $party->number }} {{ $party->name }}</h4>
        {{ Form::open(['action' => ['BallotController@auth', $ballot]]) }}
        {{ Form::hidden('password', request('password')) }}
        {{ Form::submit('Atpakaļ uz sarakstu skatu', ['class' => 'form-control btn btn-dark']) }}
        {{ Form::close() }}
        <div class="row">
            <div class="col-12">
            {{ Form::open(['action' => ['BallotController@vote', $ballot]]) }}
            {{ Form::hidden('password', request('password')) }}
            {{ Form::hidden('party', $party->id) }}
            <table class="table">
                <thead>
                    <th>Nr.</th>
                    <th>Vārds</th>
                    <th>Par</th>
                    <th>Pret</th>
                    <th>Atturos</th>
                </thead>
                <tbody>
                @foreach($party->members as $member)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $member->name }} {{ $member->surname }}</td>
                        <td>{{ Form::radio("candidates[{$member->id}]", 1) }}</td>
                        <td>{{ Form::radio("candidates[{$member->id}]", -1) }}</td>
                        <td>{{ Form::radio("candidates[{$member->id}]", 0) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if($ballot->election->isOpen())
                {{ Form::submit('Balsot par šo sarakstu', ['class' => 'form-control btn btn-primary']) }}
            @else
                @include('snippets.voting-offline')
            @endif
            {{ Form::close() }}
            </div>
        </div>
        @include('snippets.helpdesk')
    </div>
@endsection
