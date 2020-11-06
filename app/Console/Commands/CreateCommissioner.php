<?php

namespace App\Console\Commands;

use App\Election;
use App\Notifications\CommissionerAccessData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CreateCommissioner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'election:commissioner {election} {email} {name} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        if (!$this->confirm("Create a commissioner for {$election->name}?")) return 0;

        $password = Str::random();
        $user = $election->commissioners()->create([
            'email' => $this->argument('email'),
            'name' => $this->argument('name'),
            'password' => bcrypt($password),
        ],[
            'role' => $this->argument('role'),
        ]);

        Notification::route('mail', [$this->argument('email') => $this->argument('name')])
            ->notify(new CommissionerAccessData($password));

        $this->info("Created: {$user->email}:$password, access data has been mailed.");
        return 0;
    }
}
