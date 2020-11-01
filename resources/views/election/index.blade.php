@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Elections</h1>
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <th>Name</th>
                    <th>Parties</th>
                    <th>Faculty</th>
                    </thead>
                    <tbody>
                    @foreach($elections as $election)
                        <tr>
                            <td><a href="{{ route('election.show', $election) }}">{{ $election->name }}</a></td>
                            <td>{{$election->parties()->count()}}</td>
                            <td>
                            @if($election->faculty)
                                <a href="{{ route('faculty.show', $election->faculty) }}">{{ $election->faculty->abbreviation }}</a>
                            @else
                                -
                            @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
