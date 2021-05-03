<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Car extends Model
{
    use SoftDeletes;

    protected $table = 'cars';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

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

    public function setCarImageAttribute($carImageFile)
    {
        // Upload Image
        $filename = uploadFile($carImageFile, 'storage/uploads/cars/');
        $this->attributes['car_image_url'] = asset($filename);
    }

    public function getCarImageAttribute()
    {
        $carImageUrl = $this->attributes['car_image'];

        return $carImageUrl ?: env('BLANK_IMAGE_URL');
    }
}