@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>{{ $faculty->name }} ({{ $faculty->abbreviation }})</h1>
                <div class="col-12">
                    <a href="{{ route('faculty.studentList', $faculty) }}" class="btn btn-secondary">Generate student list</a>
                </div>
                <table class="table">
                    <thead>
                    <th>Number</th>
                    <th>Party</th>
                    <th>Members</th>
                    </thead>
                    <tbody>
                    @foreach($faculty->parties as $party)
                        <tr>
                            <td>{{ $party->number ?? '-' }}</td>
                            <td><a href="{{ route('party.show', $party) }}">{{ $party->name }}</a></td>
                            <td>{{ $party->members->count() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
