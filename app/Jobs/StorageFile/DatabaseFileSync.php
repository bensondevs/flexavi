<?php

namespace App\Jobs\StorageFile;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\StorageFile;

class DatabaseFileSync implements ShouldQueue
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
        // Check all files in folders are existed in database, if not delete it
        $files = Storage::disk('public')->allFiles();
        $images = array_filter($files, function ($filename) {
            $imageExtensions = ['png', 'jpeg', 'svg', 'bmp'];

            $explode = explode('.', $filename);
            $fileExtension = $explode[count($explode) - 1];
            return in_array($fileExtension, $imageExtensions);
        });
        
        foreach (array_values($images) as $path) {
            if (! $dbRecord = StorageFile::findByPath($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // Check all records in database are existed in folder, if not delete it
        foreach (StorageFile::all() as $record) {
            if (! Storage::disk('public')->exists($record->path)) {
                $record->delete();
            }
        }

        Log::info('Successfully sync database and folder file.');
    }
}
