<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Models\StorageFile;

class ExecuteWorkPhoto extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $table = 'execute_work_photos';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'photo_path',
        'photo_description',    
    ];

    protected $fillable = [
        'execute_work_id',
        'photo_condition_type',
        'photo_path',
        'photo_description',
    ];


    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($executeWorkPhoto) {
            $executeWorkPhoto->id = Uuid::generate()->string;
    	});
    }

    public function setPhotoAttribute($photoFile)
    {
        $directory = 'uploads/executeworks/';
        $photo = uploadFile($photoFile, $directory);

        $this->attributes['photo_path'] = $photo->path;
    }

    public function getPhotoUrlAttribute()
    {
        $path = $this->attributes['photo_path'];
        $file = StorageFile::findByPath($path);
        return $file->getDownloadUrl();
    }

    public static function collectAllPhotoConditionTypes()
    {
        return PhotoConditionType::asSelectArray();
    }
}