<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\ExecuteWorkPhoto\PhotoConditionType;

use App\Models\StorageFile;

use App\Observers\ExecuteWorkPhotoObserver;

class ExecuteWorkPhoto extends Model
{
    use HasFactory;
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
        self::observe(ExecuteWorkPhotoObserver::class);

    	self::creating(function ($executeWorkPhoto) {
            $executeWorkPhoto->id = Uuid::generate()->string;
    	});
    }

    public function setPhotoAttribute($photoFile)
    {
        $directory = 'uploads/execute_works/';
        $photo = uploadFile($photoFile, $directory);

        $this->attributes['photo_path'] = $photo->path;
    }

    public function getPhotoUrlAttribute()
    {
        $path = $this->attributes['photo_path'];
        if (! $file = StorageFile::findByPath($path)) {
            return null;
        }

        return $file->getDownloadUrl();
    }

    public function getPhotoConditionTypeDescriptionAttribute()
    {
        $type = $this->attributes['photo_condition_type'];
        return PhotoConditionType::getDescription($type);
    }

    public static function collectAllPhotoConditionTypes()
    {
        return PhotoConditionType::asSelectArray();
    }

    public function executeWork()
    {
        return $this->belongsTo(ExecuteWork::class);
    }
}