@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $election->name }}</h1>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <th>Party</th>
                    <th>Candidates</th>
                    </thead>
                    <tbody>
                    @foreach($election->parties as $party)
                        <tr>
                            <td><a href="{{ route('party.show', $party) }}">{{ $party->name }}</a></td>
                            <td>{{ $party->candidates()->count() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
