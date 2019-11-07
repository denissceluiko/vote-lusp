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
                        <td><a href="{{ route('protocol.results', $protocol) }}">{{ $protocol->id }}</a></td>
                        <td>{{ $protocol->faculty->name}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
