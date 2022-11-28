<?php

namespace App\Jobs\Developments;

use App\Models\Quotation\Quotation;
use App\Models\Quotation\QuotationLog;
use App\Models\User\User;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;

class CreateQuotationLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $logNames = $this->logNames();
        $columns = DB::getSchemaBuilder()->getColumnListing((new Quotation())->getTable());
        $logs = [];
        foreach (Quotation::get() as $quotation) {
            $user = User::whereHas('owner', function ($query) use ($quotation) {
                $query->where('company_id', $quotation->company_id);
            })->inRandomOrder()->first();
            for ($i = 0; $i < rand(1, 3); $i++) {
                foreach ($logNames as $logNameKey => $logName) {
                    $logs[] = [
                        'id' => generateUuid(),
                        'quotation_id' => $quotation->id,
                        'log_name' => $logNameKey,
                        'properties' => json_encode([
                            'old' => $quotation->load('customer')->toArray(),
                            'new' => $quotation->load('customer')->toArray(),
                            'column' => array_rand(array_flip($columns), 1),
                        ]),
                        'actor_type' => User::class,
                        'actor_id' => $user->id,
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(1, 30)),
                    ];
                }
            }
        }
        foreach (array_chunk($logs, 1000) as $chunk) {
            QuotationLog::insert($chunk);
        }
    }

    /**
     * Get all log names
     *
     * @return array
     */
    private function logNames(): array
    {
        $logs = Lang::get("quotation_logs");
        return Arr::dot($logs);
    }
}
