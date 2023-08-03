<?php

namespace App\Console\Commands;

use App\Console\Defaults\StartApp;
use Illuminate\Console\Command;

class AppStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lapi:start {--mail= : The super user email} {--pass= : The superuser password} {--fresh} {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the defaults data for this application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('fresh'))
            $this->call('migrate:fresh');

        if (!$this->option('mail') || !$this->option('pass')) {
            $this->warn('Fail: --mail or --pass is missing.');
            return Command::FAILURE;
        }

        (new StartApp($this->option('mail'), $this->option('pass')));

        if ($this->option('seed'))
            $this->call('db:seed');

        return Command::SUCCESS;
    }
}