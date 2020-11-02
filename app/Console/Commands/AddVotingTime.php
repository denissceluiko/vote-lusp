<?php

namespace App\Console\Commands;

use App\Election;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AddVotingTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:time {election} {start_at} {end_at}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a voting time to election (d.m.Y H:i)';

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

        $format = 'd.m.Y H:i';

        $election->votingTimes()->create([
            'start_at' => Carbon::createFromFormat($format, $this->argument('start_at')),
            'end_at' => Carbon::createFromFormat($format, $this->argument('end_at')),
        ]);

        return 0;
    }
}
