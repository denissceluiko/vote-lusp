@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table">
                    <thead>
                        <td>Name</td>
                        <td>Faculty</td>
                        <td>Size</td>
                    </thead>
                    <tbody>
                    @foreach(App\Party::all() as $party)
                        <tr>
                            <td><a href="{{ route('party.show', $party) }}">{{ $party->name }}</a></td>
                            <td>{{$party->faculty->name}}</td>
                            <td>{{$party->members->count()}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
