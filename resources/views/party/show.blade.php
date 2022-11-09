@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h6><a href="{{ route('admin.election.show', $party->election) }}">{{ $party->election->name }}</a></h6>
                <h1>Saraksts Nr. {{ $party->number ?? 'x' }} "{{ $party->name }}"</h1>
                <div class="col-12 mb-4">
                    <a href="{{ route('admin.party.ballot', $party) }}" class="btn btn-primary">Generēt biļetenu</a>
                    <a href="{{ route('admin.party.countingHelper', $party) }}" class="btn btn-primary">Lejupielādēt balsu skaitīšanas palīglapu</a>
                    <a href="{{ route('admin.party.edit', $party) }}" class="btn btn-secondary">Rediģēt</a>
                </div>
                <table class="table">
                    <thead>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Program</th>
                        <th>Student ID</th>
                        <th>Phone</th>
                        <th>Email</th>
                    </thead>
                    <tbody>
                    @foreach($party->members as $member)
                        <tr>
                            <td>{{ $loop->iteration }}.</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->surname }}</td>
                            <td>{{ $member->program ? $member->program->name : '-' }}</td>
                            <td>{{ $member->sid }}</td>
                            <td>{{ $member->phone }}</td>
                            <td>{{ $member->email }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
