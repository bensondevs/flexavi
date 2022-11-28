<?php

namespace App\Repositories\StorageFile;

use App\Models\StorageFile\StorageFile;
use App\Repositories\Base\BaseRepository;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class StorageFileRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new StorageFile());
    }

    /**
     * Upload file to certain path and disk
     *
     * @param  mixed  $content
     * @param  string  $path
     * @param  string  $disk
     * @return StorageFile|bool
     */
    public function upload($content, string $path = '', $disk = 'do')
    {
        if (Storage::put($path, $content)) {
            $file = $this->getModel();
            $file->fill([
                'disk' => $disk,
                'path' => $path,
            ]);
            $file->save();
            return $this->record($path, $disk);
        }

        return false;
    }

    /**
     * Record file insertion into database
     *
     * @param  string  $path
     * @param  string  $disk
     * @return StorageFile|bool
     */
    public function record(string $path, string $disk = 'public')
    {
        try {
            $file = $this->getModel();
            $file->path = $path;
            $file->disk = $disk;
            $file->save();
            $this->setModel($file);
            $this->setSuccess('Successfully save file.');
        } catch (Exception $e) {
            $error = $e->getMessage();
            $this->setError('Failed to save file.', $error);
        }

        return $this->getModel();
    }

    /**
     * Delete file in directory and unrecord in database
     *
     * @return  bool
     */
    public function delete()
    {
        try {
            $file = $this->getModel();
            if (Storage::exists($file->path)) {
                Storage::delete($file->path);
            }
            $file->delete();
            $this->destroyModel();
            $this->setSuccess('Successfully delete file.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to delete file.', $error);
        }

        return $this->returnResponse();
    }
}
