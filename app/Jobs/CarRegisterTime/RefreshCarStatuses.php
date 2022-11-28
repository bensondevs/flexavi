<?php

namespace App\Jobs\CarRegisterTime;

use App\Enums\Car\CarStatus;
use App\Models\Car\Car;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshCarStatuses implements ShouldQueue
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
    public function handle()
    {
        $now = now()->copy();

        /**
         * Cars that has entered the out time
         */
        Car::whereHas('registerTimes', function ($registerTime) use ($now) {
            $registerTime->where('out_time', '>=', $now);
        })->update(['status' => CarStatus::Out]);

        /**
         * Cars that has entered the return time
         */
        Car::whereHas('registerTimes', function ($registerTime) use ($now) {
            $registerTime->where('return_time', '>=', $now);
        })->update(['status' => CarStatus::Free]);
    }
}
