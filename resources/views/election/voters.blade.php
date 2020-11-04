@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $election->name }}</h1>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <th>Name</th>
                    <th>Registration time</th>
                    </thead>
                    <tbody>
                    @foreach($election->voters as $voter)
                        <tr>
                            <td>{{ $voter->student->name }} {{ $voter->student->surname }}</td>
                            <td>{{ $voter->created_at->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
