<?php

namespace App\Jobs;

use App\Ballot;
use App\Election;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ResendAssignedBallots implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Election|null $election
     */
    protected $election = null;

    /**
     * Create a new job instance.
     *
     * @param Election|null $election
     */
    public function __construct($election = null)
    {
        $this->election = $election ? intval($election) : null;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $elections = $this->election ? Election::where('id', $this->election)->get() : Election::all();
        $mailedBallots = 0;

        Log::info("Resending ballots.");
        $elections->each(function(Election $election) use ($mailedBallots) {
            Log::info("Resending ballots for {$election->name}.");
            $mailedBallots += $this->mailAssignedBallots($election);
        });

        Log::info("Resent ballot count: {$mailedBallots}");
    }

    public function mailAssignedBallots(Election $election)
    {
        $assignedBallots = $election->ballots()->status('assigned')->get();
        $assignedCount = 0;

        $assignedBallots->each(function (Ballot $ballot) use ($assignedCount) {
            if ($ballot->send()) $assignedCount++;
        });

        return $assignedCount;
    }
}
