@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 offset-lg-3">
                <h1>{{ $ballot->election->name }}</h1>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Šobrīd balsošana ir slēgta.</h5>
                        <div class="card-text">{{ $ballot->election->nextVotingTimeFormatted('Tuvākais balsošanas laiks no %from lídz %to', 'd.m.Y. H:i', 'Balsošana ir noslēgusies.') }}</div>
                    </div>
                </div>
                @include('snippets.helpdesk')
            </div>
        </div>
    </div>
@endsection
