@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>{{ $faculty->name }} ({{ $faculty->abbreviation }})</h1>
                <div class="col-12">
{{--                    <a href="{{ route('faculty.studentList', $faculty) }}" class="btn btn-secondary">Generate student list</a>--}}
{{--                    <a href="{{ route('protocol.create', $faculty) }}" class="btn btn-warning">Generate protocol</a>--}}
                </div>
                <table class="table">
                    <thead>
                    <th>Election</th>
                    <th>Parties</th>
                    </thead>
                    <tbody>
                    @foreach($faculty->elections as $election)
                        <tr>
                            <td><a href="{{ route('election.show', $election) }}">{{ $election->name }}</a></td>
                            <td>{{ $election->parties()->count() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
