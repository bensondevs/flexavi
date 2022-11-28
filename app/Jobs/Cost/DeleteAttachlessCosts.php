<?php

namespace App\Jobs\Cost;

use App\Models\Cost\Cost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteAttachlessCosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $costIds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $costIds)
    {
        $this->costIds = $costIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->costIds as $costId) {
            if ($cost = Cost::find($costId)) {
                if ($cost->costables->first()) {
                    $cost->delete();
                }
            }
        }
    }
}
