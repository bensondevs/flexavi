<?php

namespace App\Console;

use App\Jobs\Invoice\{InvoicesFirstReminder,
    InvoicesSecondReminder,
    InvoicesSendDebtCollectorReminder,
    InvoicesThirdReminder,
    SyncInvoicesStatus
};
use App\Jobs\Invoice\Sync\{FirstReminderInvoicesStatus,
    OverdueInvoicesStatus,
    SecondReminderInvoicesStatus,
    ThirdReminderInvoicesStatus
};
use App\Jobs\Subscription\{CheckCompanySubscriptions, LastDayBeforeExpiredReminder, ThreeDaysBeforeExpiredReminder};
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        /**
         * Run every day
         */
        // $schedule->job(new DatabaseFileSync)->dailyAt('00:00');
        $schedule->command('employee:update-invitation-status');

        /**
         * Subscription
         */
        $schedule->job(new CheckCompanySubscriptions)->dailyAt('00:00');
        $schedule->job(new ThreeDaysBeforeExpiredReminder)->dailyAt('00:15');
        $schedule->job(new LastDayBeforeExpiredReminder)->dailyAt('00:30');

        /**
         * Invoice status
         */
        $schedule->job(new SyncInvoicesStatus())->dailyAt('00:01');

        /**
         * Invoice reminder
         */
        $schedule->job(new InvoicesFirstReminder())->dailyAt('09:00');
        $schedule->job(new InvoicesSecondReminder())->dailyAt('09:00');
        $schedule->job(new InvoicesThirdReminder())->dailyAt('09:00');
        $schedule->job(new InvoicesSendDebtCollectorReminder())->dailyAt('09:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
