@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table class="table">
                <thead>
                <td>LUIS kods</td>
                <td>Fakultāte</td>
                <td>Nosaukums</td>
                <td>Studentu skaits</td>
                </thead>
                <tbody>
                @foreach($programs as $program)
                    <tr>
                        <td>{{ $program->code }}</td>
                        <td>{{$program->faculty->abbreviation}}</td>
                        <td><a href="{{ route('program.show', $program) }}">{{ $program->name }} ({{ $program->getStudyLevel() }})</a></td>
                        <td>{{$program->students_count}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
