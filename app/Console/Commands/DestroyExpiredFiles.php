<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\StorageFile\DestroyExpiredFiles as DestroyFiles;

class DestroyExpiredFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:cleanup';

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
        $destroy = new DestroyFiles();
        dispatch($destroy);

        $this->info('Cleaning expired files...');
    }
}
