@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @foreach($errors->all() as $error)
                <div class="alert alert-warning">{{ $error }}</div>
                @endforeach
                <h1>Balsošanas laika labošana "{{ $votingtime->election->name }}"</h1>
                {{ Form::model($votingtime, ['route' => ['admin.votingtime.update', $votingtime], 'method' => 'patch']) }}
                <div class="form-group">
                    {{ Form::label('start_at', 'Sākums') }}
                    {{ Form::text('start_at', $votingtime->start_at->format('d.m.Y H:i'), ['class' => 'form-control', 'placeholder' => 'd.m.Y H:i']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('end_at', 'Beigas') }}
                    {{ Form::text('end_at', $votingtime->end_at->format('d.m.Y H:i'), ['class' => 'form-control', 'placeholder' => 'd.m.Y H:i']) }}
                </div>
                {{ Form::submit('Saglabāt', ['class' => 'btn btn-primary']) }}
                {{ Form::close() }}
            </div>
            <div class="col-md-12 mt-4">
                {{ Form::open(['route' => ['admin.votingtime.delete', $votingtime], 'method' => 'delete']) }}
                {{ Form::submit('Dzēst', ['class' => 'btn btn-danger']) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
