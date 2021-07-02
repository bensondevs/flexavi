<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;

class StorageFile extends Model
{
    protected $table = 'storage_files';
    protected $primaryKey = 'id';
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

    public static function findByPath(string $path)
    {
        return self::where('path', $path)->first();
    }
}