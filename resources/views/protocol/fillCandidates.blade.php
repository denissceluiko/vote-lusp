@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12"><a href="{{ route('protocol.fill', $protocol) }}">Atpakaļ</a></div>
        <div class="col-md-12">
            <h1>{{ $protocol->faculty->abbreviation }} SP vēlēšanu protokols ({{ $protocol->id }}), kandidāti</h1>
        </div>
        {{ Form::open(['action' => ['ProtocolController@saveCandidates', $protocol], 'class' => 'col-md-12']) }}
        <div class="row">
            @foreach($protocol->faculty->parties()->orderBy('number')->get() as $party)
            <div class="col-md-6">
                <div class="col-12">
                    <h4>{{ $party->number }}. {{ $party->name }}</h4>
                </div>
                <table class="table">
                    <tr>
                        <th>Nr.</th>
                        <th>Vārds</th>
                        <th>Plusi</th>
                        <th>Mīnusi</th>
                    </tr>
                    @foreach($party->candidates as $candidate)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $candidate->student->name }} {{ $candidate->student->surname }}</td>
                        <td>{{ Form::text("votes_for[{$candidate->id}]", $candidate->votes_for, ['class' => 'form-control']) }}</td>
                        <td>{{ Form::text("votes_against[{$candidate->id}]", $candidate->votes_against, ['class' => 'form-control']) }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
            @endforeach
        </div>
        {{ Form::submit('Izrēķināt rezultātu', ['class' => 'btn btn-primary']) }}
        {{ Form::close() }}
    </div>
</div>
@endsection
