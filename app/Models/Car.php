<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Support\Facades\Storage;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Enums\Car\CarStatus;

class Car extends Model
{
    use SoftDeletes;
    use Searchable;

    protected $table = 'cars';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'brand',
        'model',
        'year',
        'car_name',
        'car_license',
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

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($car) {
            $car->id = Uuid::generate()->string;
    	});
    }

    public function scopeFree($car)
    {
        return $car->where('status', CarStatus::Free);
    }

    public function scopeOut()
    {
        return $car->where('status', CarStatus::Out);
    }

    public function getStatusLabelAttribute()
    {
        $status = $this->findByValue('CAR_STATUSES', $this->attributes['status']);

        return $status['label'];
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

    public static function collectAllStatuses()
    {
        return CarStatus::asSelectArray();
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