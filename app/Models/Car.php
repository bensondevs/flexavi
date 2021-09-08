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

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return CarStatus::getDescription($status);
    }

    public function setCarImageAttribute($carImageFile)
    {
        // Upload Image
        $image = uploadFile($carImageFile, '/uploads/cars/');
        $this->attributes['car_image_path'] = $image->path;
    }

    public function getCarImageUrlAttribute()
    {
        $path = $this->attributes['car_image_path'];
        return Storage::url($path);
    }

    public static function collectAllStatuses()
    {
        return CarStatus::asSelectArray();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function worklists()
    {
        return $this->belongsToMany(Worklist::class)
            ->withPivot(
                'employee_in_charge_id',
                'should_return_at',
                'returned_at'
            );
    }
}