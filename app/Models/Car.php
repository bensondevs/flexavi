<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;

use App\Traits\ModelEnums;

class Car extends Model
{
    use SoftDeletes;
    use ModelEnums;

    protected $table = 'cars';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    const CAR_STATUSES = [
        [
            'label' => 'Free',
            'value' => 'free',
        ],
        [
            'label' => 'Out',
            'value' => 'out',
        ]
    ];

    protected $fillable = [
        'company_id',
        'brand',
        'model',
        'year',
        'car_name',
        'car_license',
        'insured',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($car) {
            $car->id = Uuid::generate()->string;
    	});
    }

    public function scopeFree($car)
    {
        return $car->where('status', 'free');
    }

    public function getStatusLabelAttribute()
    {
        $status = $this->findByValue('CAR_STATUSES',$this->attributes['status']);

        return $status['label'];
    }

    public function setStatusAttribute($_status)
    {
        $status = $this->findFromAttributes(
            'CAR_STATUSES',
            $_status
        );
        if (! $status) $status = self::CAR_STATUSES[0];

        $this->status = $status['value'];
    }

    public function setCarImageAttribute($carImageFile)
    {
        // Upload Image
        $image = uploadFile($carImageFile, '/uploads/cars/');
        $this->attributes['car_image_path'] = $image->path;
    }

    public function getCarImageAttribute()
    {
        $carImageUrl = Storage::url($this->attributes['car_image_path']);
        return $carImageUrl;
    }

    public function company()
    {
        return $this->belongsTo(
            'App\Models\Company', 
            'company_id', 
            'id'
        );
    }
}