<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class RefreshApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the whole app from cache to route';

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
        shell_exec('composer dump-autoload');

        sleep(1);

        Artisan::call('cache:clear');

        sleep(1);

        Artisan::call('route:cache');

        sleep(1);

        Artisan::call('view:clear');

        sleep(1);

        Artisan::call('config:cache');

        sleep(3);

        Artisan::call('optimize');

        return $this->info('Application refreshed!');
    }
}
