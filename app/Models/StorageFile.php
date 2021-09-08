<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;

use App\Observers\StorageFileObserver;

class StorageFile extends Model
{
    protected $table = 'storage_files';
    protected $primaryKey = 'path';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'path',
        'disk',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(StorageFileObserver::class);
    }

    public function scopeExpired($query)
    {
        return $query->where('destroy_at', '<=', now());
    }

    public static function destroyFile($path, $disk = 'public')
    {
        return Storage::disk($disk)->delete($path);
    }

    public function getDownloadUrl()
    {
        $disk = $this->attributes['disk'];
        $path = $this->attributes['path'];
        return Storage::disk($disk)->url($path);
    }

    public function getFileContent()
    {
        $disk = $this->attributes['disk'];
        $path = $this->attributes['path'];
        return Storage::disk($disk)->get($path);
    }

    public static function findByPath(string $path = '', string $disk = null)
    {
        if (! $path) return;

        $disk = $disk ?: config('filesystems.default');
        if (! Storage::disk($disk)->has($path)) {
            return;
        }

        if ((! $record = self::where('path', $path)->first())) {
            $record = self::create([
                'path' => $path,
                'disk' => $disk,
            ]);
        }

        return $record;
    }

    public function deleteFile()
    {
        $disk = $this->attributes['disk'];
        $path = $this->attributes['path'];

        if (! Storage::disk($disk)->has($path)) {
            return $this->delete();
        }

        if (Storage::disk($disk)->delete($path)) {
            return $this->delete();
        }

        return false;
    }

    public function detonateFile()
    {
        $disk = $this->attributes['disk'];
        $path = $this->attributes['path'];

        return Storage::disk($disk)->delete($path);
    }

    public function setDestroyCountDown($date)
    {
        $this->attributes['destroy_at'] = $date;
        return $this->save();
    }

    public function stopDestroyCountDown()
    {
        $this->attributes['destroy_at'] = null;
        return $this->save();
    }
}