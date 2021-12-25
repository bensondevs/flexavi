<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Enums\Car\CarStatus;

class Car extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'cars';

    /**
     * The primary key of the model
     * 
     * @var string
     */
    protected $primaryKey = 'id';

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
     * Set which columns are searchable
     * 
     * @var array
     */
    protected $searchable = [
        'brand',
        'model',
        'year',
        'car_name',
        'car_license',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
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

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($car) {
            $car->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable free() method
     * This callable method will query only car with status of free
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFree(Builder $query)
    {
        return $query->where('status', CarStatus::Free);
    }

    /**
     * Create callable out() method
     * This callable method will query only car with status of out
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOut(Builder $query)
    {
        return $query->where('status', CarStatus::Out);
    }

    /**
     * Create callable attribute of "status_description"
     * This callable attribute will get the description of status
     * 
     * @return string 
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return CarStatus::getDescription($status);
    }

    /**
     * Create settable attribute of "car_image"
     * This settable attribute will set the "car_image_path" and upload
     * the image to the storage
     * 
     * @param mixed  $carImageFile
     * @return void
     */
    public function setCarImageAttribute($carImageFile)
    {
        // Upload Image
        $image = uploadFile($carImageFile, '/uploads/cars/');
        $this->attributes['car_image_path'] = $image->path;
    }

    /**
     * Create callable attribute of "car_image_url"
     * This callable attribute will generate url from the image path
     * 
     * @return string
     */
    public function getCarImageUrlAttribute()
    {
        $path = $this->attributes['car_image_path'];
        return Storage::url($path);
    }

    /**
     * Collect all possible statuses of the car
     * 
     * @static
     * @return array
     */
    public static function collectAllStatuses()
    {
        return CarStatus::asSelectArray();
    }

    /**
     * Get company of the car
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get attached worklists of the car
     */
    public function worklists()
    {
        return $this->belongsToMany(
            Worklist::class, 
            CarRegisterTime::class
        )->withPivot('should_return_at', 'marked_return_at');
    }

    /**
     * Get all registered times of the car
     */
    public function registeredTimes()
    {
        return $this->hasMany(CarRegisterTime::class);
    }

    /**
     * Get current active registered time
     */
    public function currentRegisteredTime()
    {
        return $this->hasOne(CarRegisterTime::class)->whereNull('marked_return_at');
    }

    /**
     * Get all registered employee that have been in the car
     */
    public function registeredTimeEmployees()
    {
        return $this->hasManyThrough(
            CarRegisterTimeEmployee::class, 
            CarRegisterTime::class
        );
    }

    /**
     * Change car status to be out
     * 
     * @return bool
     */
    public function setOut()
    {
        $this->attributes['status'] = CarStatus::Out;
        return $this->save();
    }

    /**
     * Change car status to be free
     * 
     * @return bool
     */
    public function setFree()
    {
        $this->attributes['status'] = CarStatus::Free;
        return $this->save();
    }
}