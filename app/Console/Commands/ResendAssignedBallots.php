<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResendAssignedBallots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:resend';

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
        \App\Jobs\ResendAssignedBallots::dispatch();
        return 0;
    }
}
