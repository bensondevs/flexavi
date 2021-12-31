<?php

namespace App\Traits;

use App\Models\StorageFile;

trait HasFile 
{
    /**
     * Get file attached to the current model
     * 
     * @param  string  $path
     * @return \App\Models\StorageFile
     */
    public function getFile(string $path)
    {
        return StorageFile::findByPath($path);
    }

    /**
     * Get URL of uploaded file
     * 
     * @param string $path
     * @return string
     */
    public function getFileUrl(string $path)
    {
        return StorageFile::findByPath($path)->getDownloadUrl();
    }

    /**
     * Replace file with new file
     * 
     * @param string $currentPath
     * @param string $newPath
     * @return void
     */
    public function switchFile(string $currentPath, string $newPath)
    {
        StorageFile::findByPath($currentPath)->delete();
        StorageFile::create($newPath);
    }

    /**
     * Destroy file
     * 
     * @param string $file
     * @return bool
     */
    public function destroyFile(string $path)
    {
        return StorageFile::findByPath($path)->delete();
    }
}