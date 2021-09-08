<?php

namespace App\Traits;

use App\Models\StorageFile;

trait HasFile 
{
    public function getFile($path)
    {
        $file = StorageFile::findByPath($path)
        return $file;
    }

    public function getFileUrl($path)
    {
        $url = StorageFile::findByPath($path)->getDownloadUrl()
        return $url ?: null;
    }

    public function switchFile($currentPath, $newPath)
    {
        StorageFile::findByPath($currentPath)->delete();
        StorageFile::create($newPath);
    }

    public function destroyFile($path)
    {
        return StorageFile::findByPath($path)->delete();
    }
}