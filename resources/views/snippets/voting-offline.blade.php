@if($election->isFinished())
    <div class="alert alert-info">Balsošana {{ $election->name }} ir noslēgusies</div>
@elseif(!$election->isOpen())
    <div class="alert alert-danger">Šobrīd var tikai apskatīt <a href="{{ route('election.show', $election) }}">kandidātu sarakstus</a>. {{ $election->nextVotingTimeFormatted('Tuvākais balsošanas laiks no %from lídz %to', 'd.m.Y. H:i', 'Balsošana ir noslēgusies.') }}</div>
@endif
