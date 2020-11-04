<?php

namespace App\Console\Commands;

use App\Election;
use App\Jobs\ResendAssignedBallots as ResendJob;
use Illuminate\Console\Command;

class ResendAssignedBallots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:resend {election?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resends assigned but unopened ballots';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!empty($this->argument('election'))) {
            $election = Election::find($this->argument('election'));
            if (!$election) {
                $this->warn('Election not found: ID '.$this->argument('election'));
                return 1;
            }
            $this->info('Resending emails for '.$election->name);
            ResendJob::dispatch(intval($this->argument('election')));
        } else {
            $this->info('Resending emails for all elections');
            ResendJob::dispatch();
        }
        return 0;
    }
}
