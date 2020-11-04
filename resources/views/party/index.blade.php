@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table">
                    <thead>
                        <td>Nosaukums</td>
                        <td>Vēlēšanas</td>
                        <td>Kandidātu skaits</td>
                    </thead>
                    <tbody>
                    @foreach($parties as $party)
                        <tr>
                            <td><a href="{{ route('admin.party.show', $party) }}">{{ $party->name }}</a></td>
                            <td><a href="{{ route('admin.faculty.show', $party->election) }}">{{$party->election->name}}</a></td>
                            <td>{{$party->members->count()}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
