<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\StorageFile\DatabaseFileSync;

class SyncDatabaseFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronise file and database existence.';

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
        $sync = new DatabaseFileSync();
        dispatch($sync);

        $this->info('Syncronizing files with database records...');
    }
}
