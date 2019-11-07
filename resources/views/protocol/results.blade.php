@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12"><a href="{{ route('protocol.fillCandidates', $protocol) }}">Atpakaļ</a></div>
        <div class="col-md-12">
            <h1>{{ $protocol->faculty->abbreviation }} SP vēlēšanu protokols ({{ $protocol->id }}), rezultāti</h1>
        </div>
        <h2>Pamatinformācija</h2>
        <dl class="row">
            <dt class="col-8">FSP biedru skaits</dt>
            <dd class="col-4">{{ $protocol->member_count }}</dd>
            <dt class="col-8">Balsstiesīgo skaits</dt>
            <dd class="col-4">{{ $protocol->voters_eligible }}</dd>
            <dt class="col-8">Izsniegto zīmju skaits</dt>
            <dd class="col-4">{{ $protocol->voters_attended }}</dd>
            <dt class="col-8">Urnā atrasto zīmju skaits</dt>
            <dd class="col-4">{{ $protocol->ballots_found }}</dd>
            <dt class="col-8">Nederīgo zīmju skaits</dt>
            <dd class="col-4">{{ $protocol->ballots_void }}</dd>
        </dl>
        <h2>Sarakstu dati</h2>
        <div class="row">
            @foreach($protocol->faculty->parties()->orderBy('number')->get() as $party)
            <div class="col-md-6">
                <div class="col-12">
                    <h4>Nr. {{ $party->number }}. | {{ $party->name }}</h4>
                </div>
                <dl class="row">
                    <dt class="col-8">Par sarakstu nodoto zīmju skaits</dt>
                    <dd class="col-4">{{ $party->ballots_valid }}</dd>
                    <dt class="col-8">Par sarakstu nodoto grozīto zīmju skaits</dt>
                    <dd class="col-4">{{ $party->ballots_changed }}</dd>
                    <dt class="col-8">Par sarakstu nodoto negrozīto zīmju skaits</dt>
                    <dd class="col-4">{{ $party->ballots_unchanged }}</dd>
                </dl>
                <table class="table">
                    <tr>
                        <th>Nr.</th>
                        <th>Vārds</th>
                        <th>Plusi</th>
                        <th>Mīnusi</th>
                        <th>Kopā</th>
                    </tr>
                    @foreach($party->candidates as $candidate)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $candidate->student->name }} {{ $candidate->student->surname }}</td>
                            <td>{{ $candidate->votes_for }}</td>
                            <td>{{ $candidate->votes_against }}</td>
                            <td>{{ $candidate->votes_for - $candidate->votes_against }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @endforeach
        </div>
{{--        <h2>Mandātu sadalījums sarakstiem</h2>--}}
{{--        <dl class="row">--}}
{{--            @foreach($protocol->getMandates() as $party)--}}
{{--                <dt class="col-6">{{ $party->name }}</dt>--}}
{{--                <dd class="col-6">{{ $party->name }}</dd>--}}
{{--            @endforeach--}}
{{--        </dl>--}}
        <h2>Mandātu sadalījums cilvēkiem</h2>
        <table class="table">
            <tr>
                <th>Nr.</th>
                <th>Saraksts</th>
                <th>Dalījums</th>
                <th>Vārds</th>
            </tr>
            @foreach($protocol->getDistribution() as $position)
                <tr class="{{ $loop->iteration > $protocol->member_count ? 'table-warning' : 'table-success' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $position['candidate']->party->name }} (Nr.{{ $position['candidate']->party->number }})</td>
                    <td>{{ $position['candidate']->student->name }} {{ $position['candidate']->student->surname }}</td>
                    <td>{{ $position['div'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection
