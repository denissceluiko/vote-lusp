@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Studenti</h1>
            <div class="col-12">
                {{ Form::open(['action' => 'StudentController@search', 'method' => 'get', 'class' => 'form-inline mb-3']) }}
                <div class="form-group">
                    {{ Form::text('query', request('query'), ['class' => 'form-control']) }}
                    {{ Form::submit('Meklēt', ['class' => 'btn btn-primary', 'placeholder' => 'Vārds/stud.apl.nr']) }}
                </div>
                {{ Form::close() }}
            </div>
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <td class="d-none d-md-table-cell">No.</td>
                    <td>Vārds</td>
                    <td class="d-none d-md-table-cell">Fakultāte</td>
                    <td>Apl. nr.</td>
                    <td>Statuss</td>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td class="d-none d-md-table-cell">{{ ($students->currentPage()-1)*$students->perPage() + $loop->iteration }}</td>
                            <td>{{ $student->name }} {{ $student->surname }}</td>
                            <td class="d-none d-md-table-cell"><a href="{{ route('faculty.show', $student->faculty) }}">{{ $student->faculty->abbreviation }}</a></td>
                            <td>{{ $student->sid }}</td>
                            <td>{{ $student->status }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="col-12 d-none d-md-block">
                    {{ $students->links() }}
                </div>
                <div class="col-12 d-md-none">
                    {{ $students->appends(request()->only('query'))->links('pagination::simple-bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
