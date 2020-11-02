<?php

namespace App\Console\Commands;

use App\Election;
use Illuminate\Console\Command;

class GenerateBallots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:ballots {election}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates ballots for an election';

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
        $election = Election::find($this->argument('election'));
        if (!$election) {
            $this->error('Election not found');
            return 1;
        }

        $count = $election->generateBallots();
        $this->info("$count ballots generated for {$election->name}.");
        return 0;
    }
}
