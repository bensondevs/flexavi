<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class WorkConditionPhoto extends Model
{
    protected $table = 'work_condition_photos';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'uploader_id',
        'work_id',
        'photo_type',
        'photo_url',
        'photo_description',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($workConditionPhoto) {
            $workConditionPhoto->id = Uuid::generate()->string;
    	});
    }

    public function setPhotoAttribute($photoFile)
    {
        $path = 'storage/uploads/works/conditions/';
        $uploadedPhotoPath = uploadFile($photoFile, $path);
        $photoUrl = asset($uploadedPhotoPath);

        $this->attribute['photo_url'] = $photoUrl;
    }

    public function uploader()
    {
        return $this->hasOne(
            'App\Models\User', 
            'id', 
            'uploader_id'
        );
    }

    public function work()
    {
        return $this->belongsTo(
            'App\Models\Work', 
            'work_id', 
            'id'
        );
    }
}