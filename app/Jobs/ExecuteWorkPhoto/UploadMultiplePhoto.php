<?php

namespace App\Jobs\ExecuteWorkPhoto;

use App\Models\ExecuteWork\ExecuteWorkPhoto;
use App\Repositories\ExecuteWork\ExecuteWorkPhotoRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadMultiplePhoto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 7200;

    private $photoCollection;

    private $repository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $photoDataArray = [])
    {
        $this->photoCollection = collect($photoDataArray);
        $this->repository = new ExecuteWorkPhotoRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->photoCollection as $photoData) {
            $this->repository->setModel(new ExecuteWorkPhoto);
            $this->repository->uploadPhoto($photoData);
        }
    }
}
