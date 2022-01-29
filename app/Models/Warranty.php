<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Observers\WarrantyObserver as Observer;

use App\Enums\Warranty\WarrantyStatus;

class Warranty extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

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
    protected $searchable = [
        'problem_description',
        'fixing_description',
        'internal_note',
        'customer_note',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var bool
     */
    protected $fillable = [
        'company_id',
        'appointment_id',
        'status',
        'problem_description',
        'fixing_description',
        'internal_note',
        'customer_note',
        'amount',
        'paid_amount',
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
     * Create callable "formatted_amount" attribute
     * This callable attribute will return currency formatted amount
     * 
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        $amount = $this->attributes['amount'];
        return currency_format($amount);
    }

    /**
     * Create callable "formatted_paid_amount" attribute
     * This callable attribute will return currency formatted
     * paid amount of warranty
     * 
     * @return string
     */
    public function getFormattedPaidAmountAttribute()
    {
        $paidAmount = $this->attributes['paid_amount'];
        return currency_format($paidAmount);
    }

    /**
     * Create callable "unpaid_amount" attribute
     * This callable attribute will return amount of unpaid amount
     * 
     * @return double
     */
    public function getUnpaidAmountAttribute()
    {
        $amount = $this->attributes['amount'];
        $paidAmount = $this->attributes['paid_amount'];

        return $amount - $paidAmount;
    }

    /**
     * Create callable "formatted_unpaid_amount" attribute
     * This callable attribute will return currency-
     * formatted unpaid amount
     * 
     * @return string
     */
    public function getFormattedUnpaidAmountAttribute()
    {
        $unpaidAmount = $this->getUnpaidAmountAttribute();
        return currency_format($unpaidAmount);
    }

    /**
     * Get company of the warranty
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get appointment of the warranty
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get target appointment that being warranted
     */
    public function forAppointment()
    {
        return $this->belongsTo(Appointment::class, 'for_appointment_id');
    }

    /**
     * Get warranty works
     */
    public function warrantyWorks()
    {
        return $this->hasMany(WarrantyWork::class);
    }

    /**
     * Get work thats warrantied
     */
    public function work()
    {
        return $this->hasManyThrough(Work::class, WarrantyWork::class);
    }
}