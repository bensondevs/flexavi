<?php

namespace App\Jobs\Developments;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceLog;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateInvoiceLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $mailable = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $rawLogs = [];
        $invoices = Invoice::withTrashed()->get();
        foreach ($invoices as $invoice) {
            $actor = User::inRandomOrder()->first();
            for ($k = 0; $k < 50; $k++) {
                $message = $this->generateLogMessage($actor);
                $rawLogs[] = [
                    'id' => generateUuid(),
                    'invoice_id' => $invoice->id,
                    'actor_id' => $actor->id,
                    'actor_type' => get_class($actor),
                    'message' => json_encode([
                        'en' => $message['en'],
                        'nl' => $message['nl'],
                    ]),
                    'created_at' => now()->subDays(rand(1, 15)),
                    'updated_at' => now()->subDays(rand(1, 15)),
                ];
            }
        }
        foreach (array_chunk($rawLogs, 1000) as $chunk) {
            InvoiceLog::insert($chunk);
        }
    }

    /**
     * Generate log message
     *
     * @param User $user
     * @return string[]
     */
    private function generateLogMessage(User $user): array
    {
        $logs = [
            'en' => [
                $user->fullname . ' has updated invoice data',
                $user->fullname . ' has deleted invoice',
                $user->fullname . ' has restored invoice',
                'Invoice status change from Draft to Sent',
                $user->fullname . ' resend Invoice to customer mail “customer@mail.com”'
            ],
            'nl' => [
                $user->fullname . ' heeft factuurgegevens bijgewerkt',
                $user->fullname . ' heeft factuur verwijderd',
                $user->fullname . ' heeft factuur hersteld',
                'Factuurstatus is gewijzigd van Draft naar Sent',
                $user->fullname . ' heeft factuur opnieuw verzonden naar klant mail “customer@mail.com”'
            ]
        ];

        $rand = rand(0, 4);
        return [
            'nl' => $logs['nl'][$rand],
            'en' => $logs['en'][$rand]
        ];
    }
}
