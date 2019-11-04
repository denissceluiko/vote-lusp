@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table">
                    <thead>
                    <th>Name</th>
                    <th>Abbreviation</th>
                    </thead>
                    <tbody>
                    @foreach(App\Faculty::all() as $faculty)
                        <tr>
                            <td><a href="{{ route('faculty.show', $faculty) }}">{{ $faculty->name }}</a></td>
                            <td>{{$faculty->abbreviation}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
