<?php

namespace App\Traits;

use App\Models\StorageFile\StorageFile;

trait HasFile
{
    /**
     * Get file attached to the current model
     *
     * @param string $path
     * @return StorageFile
     */
    public function getFile(string $path): StorageFile
    {
        return StorageFile::findByPath($path);
    }

    /**
     * Get URL of uploaded file
     *
     * @param string $path
     * @return string
     */
    public function getFileUrl(string $path): string
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
    public function switchFile(string $currentPath, string $newPath): void
    {
        StorageFile::findByPath($currentPath)->delete();
        StorageFile::create((array)$newPath);
    }

    /**
     * Destroy file
     *
     * @param string $path
     * @return bool
     */
    public function destroyFile(string $path): bool
    {
        return StorageFile::findByPath($path)->delete();
    }
}
