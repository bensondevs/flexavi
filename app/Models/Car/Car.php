<?php

namespace App\Models\Car;

use App\Enums\Car\CarStatus as Status;
use App\Models\Company\Company;
use App\Models\Worklist\Worklist;
use App\Observers\CarObserver as Observer;
use App\Rules\Helpers\Media;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, HasManyThrough, HasOne};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Car extends Model
{

    use HasFactory;
    use SoftDeletes;
    use \App\Traits\Searchable;


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
    public $searchableFields = [
        'brand',
        'model',
        'year',
        'car_name',
        'car_license',
        'insurance_tax',
        'apk'
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
        'max_passanger',
        'insurance_tax',
        'apk'
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
        self::observe(Observer::class);
    }

    /**
     * Create callable free() method
     * This callable method will query only car with status of free
     *
     * @param Builder  $query
     * @return Builder
     */
    public function scopeFree(Builder $query)
    {
        return $query->where('status', Status::Free);
    }

    /**
     * Create callable out() method
     * This callable method will query only car with status of out
     *
     * @param Builder  $query
     * @return Builder
     */
    public function scopeOut(Builder $query)
    {
        return $query->where('status', Status::Out);
    }

    /**
     * Create callable attribute of "status_description"
     * This callable attribute will get the description of status
     *
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->fresh()->attributes['status'];

        return Status::getDescription($status);
    }

    /**
     * Set the default image placeholder file
     *
     * @return string
     */
    public static function placeholder()
    {
        $placeholderFilename = 'placeholder-fleet.webp';
        $filename = Media::randomCustomFilename(
            explode('.', $placeholderFilename)[1]
        );
        Storage::copy(
            $placeholderFilename,
            "cars/$filename"
        );

        return $filename;
    }

    /**
     * Perform download action of the image file
     *
     * @throws HttpException
     * @return StreamedResponse
     */
    public function downloadCarImage()
    {
        if (Storage::missing("cars/$this->car_image_path")) {
            abort(404);
        }

        return Storage::download("cars/$this->car_image_path");
    }

    /**
     * Create settable attribute of "car_image"
     * This settable attribute will set the "car_image_path" and upload
     * the image to the storage
     *
     * @param UploadedFile  $file
     * @return void
     */
    public function setCarImageAttribute(UploadedFile $file)
    {
        $filename = Media::randomFilename($file);
        Storage::putFileAs('cars', $file, $filename);
        $this->attributes['car_image_path'] = $filename;
    }

    /**
     * Create callable attribute of "car_image_url"
     * This callable attribute will generate url from the image path
     *
     * @return string|null
     */
    public function getCarImageUrlAttribute()
    {
        if (Storage::missing("cars/$this->car_image_path")) {
            return null;
        }

        return Storage::url("cars/$this->car_image_path");
    }

    /**
     * Collect all possible statuses of the car
     *
     * @static
     * @return array
     */
    public static function collectAllStatuses()
    {
        return Status::asSelectArray();
    }

    /**
     * Get company of the car
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get attached worklists of the car
     *
     * @return BelongsToMany
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
     *
     * @return HasMany
     */
    public function registeredTimes()
    {
        return $this->hasMany(CarRegisterTime::class);
    }

    /**
     * Get current active registered time
     *
     * @return HasOne
     */
    public function currentRegisteredTime()
    {
        return $this->hasOne(CarRegisterTime::class)->whereNull(
            'marked_return_at'
        );
    }

    /**
     * Get all registered employee that have been in the car
     *
     * @return HasManyThrough
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
        $this->attributes['status'] = Status::Out;

        return $this->save();
    }

    /**
     * Change car status to be free
     *
     * @return bool
     */
    public function setFree()
    {
        $this->attributes['status'] = Status::Free;

        return $this->save();
    }

    /**
     * Create callable attribute of "formatted_insurance_tax"
     * This callable attribute will return the insureance tax
     *
     * @return string
     */
    public function getFormattedInsuranceTaxAttribute()
    {
        return currencyFormat($this->attributes['insurance_tax']);
    }
}
