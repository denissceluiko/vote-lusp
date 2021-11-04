@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>Balsošanas laika pievienošana "{{ $election->name }}"</h1>
                {{ Form::open(['route' => ['admin.votingtime.store', $election], 'method' => 'post']) }}
                <div class="form-group">
                    {{ Form::label('name_short', 'Sākums') }}
                    {{ Form::text('start_at', null, ['class' => 'form-control', 'placeholder' => 'd.m.Y H:i']) }}
                </div>
                <div class="form-group">
                    {{ Form::label('seats', 'Beigas') }}
                    {{ Form::text('end_at', null, ['class' => 'form-control', 'placeholder' => 'd.m.Y H:i']) }}
                </div>
                {{ Form::submit('Pievienot', ['class' => 'btn btn-primary']) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
