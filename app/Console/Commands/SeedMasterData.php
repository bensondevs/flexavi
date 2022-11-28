<?php

namespace App\Console\Commands;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

class SeedMasterData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:master-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to seed only master data.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->output = new ConsoleOutput();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('<fg=yellow>Seeding master data...</>');

        $seeder = new DatabaseSeeder();
        $seeder->seedMasterData();

        $this->info('Seeding master data completed.');

        return Command::SUCCESS;
    }
}
