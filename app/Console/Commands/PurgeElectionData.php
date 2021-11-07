<?php

namespace App\Console\Commands;

use App\Election;
use Illuminate\Console\Command;

class PurgeElectionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:purge {election?} {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purges data for the specified election';

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
        if ($this->option('all') && !$this->confirm('Really purge all election data?')) {
            return Command::SUCCESS;
        }

        $level = $this->choice('How deep is the purge?', [
            'votes',
            'candidates',
            'parties',
            'elections'
        ], 0);

        if ($this->option('all')) {
            $this->purgeAll($level);
        } elseif ($this->argument('election')){
            $election = Election::find($this->argument('election'));

            if (!$election) {
                $this->error('Election with this id does not exist.');
                return Command::FAILURE;
            }

            $this->purge($election, $level);
        } else {
            return Command::INVALID;
        }

        return Command::SUCCESS;
    }

    public function purge(Election $election, string $level)
    {
        $this->info("Purging {$election->name} ({$election->id}) @ $level.");

        // Votes & voters
        if (!in_array($level, ['votes','candidates','parties','elections'])) return;
        $election->ballots()->delete();
        $election->voters()->delete();

        // Candidates
        if (!in_array($level, ['candidates','parties','elections'])) return;
        $election->candidates()->delete();

        // Parties
        if (!in_array($level, ['parties','elections'])) return;
        $election->parties()->delete();

        // Election
        if ($level != 'elections') return;
        $election->votingTimes()->delete();
        $election->delete();
    }

    public function purgeAll(string $level)
    {
        $this->withProgressBar(Election::all(), function ($election) use ($level) {
            $this->purge($election, $level);
        });
    }
}
