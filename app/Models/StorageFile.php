<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Webpatser\Uuid\Uuid;

use App\Observers\StorageFileObserver;

class StorageFile extends Model
{
    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'storage_files';

    /**
     * The primary key of the model
     * 
     * @var string
     */
    protected $primaryKey = 'path';

    /**
     * Timestamp recording
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use increment or not
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'path',
        'disk',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(StorageFileObserver::class);
    }

    /**
     * Create callable method of expired()
     * This callable method will allow to query only expired storage file
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired(Builder $query)
    {
        return $query->where('destroy_at', '<=', now());
    }

    /**
     * Destroy file by path. 
     * Set the second parameter to change the filesystems configuration.
     * 
     * @param string  $path
     * @param string  $disk
     * @return bool
     */
    public static function destroyFile(string $path, string $disk = 'public')
    {
        return Storage::disk($disk)->delete($path);
    }

    /**
     * Get download url to access the file in storage
     * 
     * @return string
     */
    public function getDownloadUrl()
    {
        $disk = $this->attributes['disk'];
        $path = $this->attributes['path'];
        return Storage::disk($disk)->url($path);
    }

    /**
     * Get file content of the uploaded storage file
     * 
     * @return Illuminate\Support\Facades\Storage
     */
    public function getFileContent()
    {
        $disk = $this->attributes['disk'];
        $path = $this->attributes['path'];
        return Storage::disk($disk)->get($path);
    }

    /**
     * Find storage file by path supplied into the first parameter.
     * If file existed in storage but not found in directory, 
     * This method will record it to database.
     * 
     * @param string|null  $path
     * @param string|null  $disk
     * @return \App\Models\StorageFile
     */
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

    /**
     * Delete stored file and record in database
     * 
     * @return bool
     */
    public function deleteFile()
    {
        $disk = $this->attributes['disk'];
        $path = $this->attributes['path'];

        if (! Storage::disk($disk)->has($path)) {
            return $this->delete();
        }

        return Storage::disk($disk)->delete($path) ? $this->delete() : false;
    }

    /**
     * Detonate file only without deleting the record in database
     * 
     * @return bool
     */
    public function detonateFile()
    {
        $disk = $this->attributes['disk'];
        $path = $this->attributes['path'];

        return Storage::disk($disk)->delete($path);
    }

    /**
     * Set count down to destroy the record
     * 
     * @param mixed  $date
     * @return bool
     */
    public function setDestroyCountDown($date)
    {
        $this->attributes['destroy_at'] = $date;
        return $this->save();
    }

    /**
     * Stop destroy file count down
     * 
     * @return bool
     */
    public function stopDestroyCountDown()
    {
        $this->attributes['destroy_at'] = null;
        return $this->save();
    }
}