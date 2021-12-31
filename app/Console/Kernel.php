<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Jobs\StorageFile\{
    DatabaseFileSync,
    DestroyExpiredFiles
};
use App\Jobs\CarRegisterTime\RefreshCarStatuses;
use App\Jobs\Invoice\SyncInvoiceStatuses;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /**
         * Run every day
         */
        $schedule->job(new DatabaseFileSync)->daily();

        /**
         * Run every three hours
         */
        $schedule->job(new SyncInvoiceStatuses)->everyThreeHours();
        
        /**
         * Run every hour
         */
        $schedule->job(new DestroyExpiredFiles)->hourly();

        /**
         * Run every minute
         */
        $schedule->job(new RefreshCarStatuses)->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
