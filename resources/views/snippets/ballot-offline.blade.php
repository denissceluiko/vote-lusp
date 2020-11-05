@if(!$ballot->election->isOpen())
    <div class="alert alert-danger">Šobrīd var tikai apskatīt <a href="{{ route('election.show', $ballot->election) }}">kandidātu sarakstus</a>. {{ $ballot->election->nextVotingTimeFormatted('Tuvākais balsošanas laiks no %from lídz %to', 'd.m.Y. H:i', 'Balsošana ir noslēgusies.') }}</div>
@endif
