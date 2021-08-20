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
}