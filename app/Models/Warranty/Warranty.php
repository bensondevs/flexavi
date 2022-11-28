<?php

namespace App\Models\Warranty;

use App\Enums\Warranty\WarrantyStatus;
use App\Models\Appointment\Appointment;
use App\Models\Company\Company;
use App\Models\ExecuteWork\WorkWarranty;
use App\Observers\WarrantyObserver as Observer;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Warranty extends Model
{

    use HasFactory;
    use SoftDeletes;
    use HasRelationships;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'warranties';

    /**
     * Table name primary key
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
    public $searchableFields = [];

    /**
     * Set which columns are mass fillable
     *
     * @var bool
     */
    protected $fillable = [
        'company_id',
        'appointment_id',
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
        self::observe(Observer::class);
    }

    /**
     * Create callable "status_description" attribute
     * This callable attribute will return status enum description
     *
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];

        return WarrantyStatus::getDescription($status);
    }


    /**
     * Create callable "total_price" attribute
     * This callable attribute will return
     *  total price
     *
     * @return string
     */
    public function getTotalPriceAttribute()
    {
        return $this->works()->sum('total_price');
    }

    /**
     * Create callable "formatted_total_price" attribute
     * This callable attribute will return currency-
     * formatted total price
     *
     * @return string
     */
    public function getFormattedTotalPriceAttribute()
    {
        return currency_format($this->getTotalPriceAttribute());
    }

    /**
     * Create callable "total_company_paid" attribute
     * This callable attribute will return
     *  total company paid
     *
     * @return string
     */
    public function getTotalCompanyPaidAttribute()
    {
        return $this->workAppointmentWarranties()->sum('company_paid');
    }

    /**
     * Create callable "formatted_total_company_paid" attribute
     * This callable attribute will return currency-
     * formatted total company paid
     *
     * @return string
     */
    public function getFormattedTotalCompanyPaidAttribute()
    {
        return currency_format($this->getTotalCompanyPaidAttribute());
    }

    /**
     * Create callable "total_customer_paid" attribute
     * This callable attribute will return
     *  total customer paid
     *
     * @return string
     */
    public function getTotalCustomerPaidAttribute()
    {
        return $this->workAppointmentWarranties()->sum('customer_paid');
    }

    /**
     * Create callable "formatted_total_customer_paid" attribute
     * This callable attribute will return currency-
     * formatted total customer paid
     *
     * @return string
     */
    public function getFormattedTotalCustomerPaidAttribute()
    {
        return currency_format($this->getTotalCustomerPaidAttribute());
    }

    /**
     * Get company of the warranty
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get appointment of the warranty
     *
     * @return BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get warranty appointments
     *
     * @return HasMany
     */
    public function warrantyAppointments()
    {
        return $this->hasMany(WarrantyAppointment::class);
    }

    /**
     * Get warranty works
     *
     * @return HasMany
     */
    public function works()
    {
        return $this->hasManyDeep(
            WorkWarranty::class,
            [
                WarrantyAppointment::class,
                WarrantyAppointmentWork::class
            ],
            [
                'warranty_id',
                null,
                'id'
            ],
            [
                'id',
                'id',
                'work_warranty_id'
            ]
        );
    }

    /**
     * Get work appointment warranties
     *
     * @return HasMany
     */
    public function workAppointmentWarranties()
    {
    return $this->hasManyThrough(
        WarrantyAppointmentWork::class,
        WarrantyAppointment::class
    );
}
}
