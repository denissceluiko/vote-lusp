@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                <td>Nr.</td>
                <td>Fakultāte</td>
                </thead>
                <tbody>
                @foreach(App\Protocol::all() as $protocol)
                    <tr>
                        <td>{{ $protocol->id }}</td>
                        <td><a href="{{ route('admin.election.show', $protocol->election) }}">{{ $protocol->election->name}}</a></td>
                        <td><a href="{{ route('admin.protocol.results', $protocol) }}">Protokols</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
