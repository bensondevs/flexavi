<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\QuotationObserver as Observer;
use App\Casts\QuotationDamageCausesCast;
use App\Enums\Quotation\{
    QuotationType as Type,
    QuotationStatus as Status,
    QuotationCanceller as Canceller,
    QuotationDamageCause as DamageCause,
    QuotationPaymentMethod as PaymentMethod
};

class Quotation extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'quotations';

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
        'quotation_number',
        'contact_person',
        'address',
        'zipcode',
        'address',
        'phone_number',
        'quotation_description',
        'honor_note',
        'cancellation_reason',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'customer_id',
        'appointment_id',
        
        'type',

        'quotation_date',
        'quotation_number',
        
        'contact_person',

        'address',
        'zipcode',
        'phone_number',
        
        'quotation_description',

        'amount',
        'vat_percentage',
        'discount_amount',
        'total_amount',

        'expiry_date',
        'status',

        'payment_method',

        'honor_note',

        'canceller',
        'cancellation_reason',
    ];

    /**
     * Set which attribute that should be casted
     * 
     * @var array
     */
    protected $cast = [
        /*'is_signed' => 'boolean',
        'honored_at' => 'datetime',
        'cancelled_at' => 'datetime',*/
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Create callable "type_description" attribute
     * This attribute will return quotation type description
     * from enum.
     * 
     * @return string
     */
    public function getTypeDescriptionAttribute()
    {
        return Type::getDescription($this->attributes['type']);
    }

    /**
     * Create callable "formatted_amount" attribute
     * This attribute will return quotation amount
     * in currency formatted form.
     * 
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        return currency_format($this->attributes['amount']);
    }

    /**
     * Create callable "formatted_vat_percentage" attribute
     * This callable attribute will return percentage of Quotation VAT
     * 
     * @return string
     */
    public function getFormattedVatPercentageAttribute()
    {
        return $this->attributes['vat_percentage'] . '%';
    }

    /**
     * Create callable "vat_amount" attribute
     * This callable attribute will return total amount of Quotation VAT
     * 
     * @return double
     */
    public function getVatAmountAttribute()
    {
        $amount = $this->attributes['amount'];
        $percentage = $this->attributes['vat_percentage'];

        return ($percentage / 100) * $amount;
    }

    /**
     * Create callable "formatted_vat_amount" attribute
     * This callable attribute will return total amount of Quotation VAT
     * In version of formatted currency
     * 
     * @return string
     */
    public function getFormattedVatAmountAttribute()
    {
        $vatAmount = $this->getVatAmountAttribute();

        return currency_format($vatAmount);
    }

    /**
     * Create callable "formatted_expiry_date" attribute
     * This callable attribute will return formatted expiry date
     * Example resulted format will be: January 01, 2021
     * 
     * @return string
     */
    public function getFormattedExpiryDateAttribute()
    {
        $expiryDate = $this->attributes['expiry_date'];

        return carbon($expiryDate)->format('M d, Y');
    }

    /**
     * Create callable "status_description" attribute
     * This callable attribute will return description of status enum
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];

        return Status::getDescription($status);
    }

    /**
     * Create callable "payment_method_description" attribute
     * This callable attribute will return payment method description
     * based on enum value recorded
     * 
     * @return string
     */
    public function getPaymentMethodDescriptionAttribute()
    {
        $method = $this->attributes['payment_method'];

        return PaymentMethod::getDescription($method);
    }

    /**
     * Create callable "canceller_description" attribute
     * This callable attribute will return canceller description
     * based on enum value recorded
     * 
     * @return string
     */
    public function getCancellerDescriptionAttribute()
    {
        $canceller = $this->attributes['canceller'];

        return Canceller::getDescription($canceller);
    }

    /**
     * Create settable "damage_causes" attribute
     * This settable attribute will allow set multiple damage causes 
     * using array as the value
     * 
     * @param array  $damageCauses
     * @return void
     */
    public function setDamageCausesAttribute(array $damageCauses = [])
    {
        $this->attributes['damage_causes'] = json_encode($damageCauses);
    }

    /**
     * Create settable "discount_percentage" attribute
     * This settable attribute will allow set discount percentage 
     * like real percentage (eg: 30.5%)
     * 
     * @param string  $percentage
     * @return void
     */
    public function setDiscountPercentageAttribute(string $percentage)
    {
        $percentage = str_replace('%', '', $percentage);
        $percentage = (float) $percentage;

        $amount = $this->attributes['amount'];
        $this->attributes['discount_amount'] = $amount * ($percentage / 100);
    }

    /**
     * Create settable "amount" attribute
     * This settable attribute will allow set quotation amount 
     * and convert it as double
     * 
     * @param mixed  $amount
     * @return void
     */
    public function setAmountAttribute($amount)
    {
        $this->attributes['amount'] = (double) $amount;
        $this->calculateTotal();
    }

    /**
     * Get appointment of quotation
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get target customer of quotation
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Create callable "works" attribute and get 
     * quoted works model data
     */
    public function works()
    {
        return $this->morphToMany(Work::class, 'workable');
    }

    /**
     * Get attachments of the quotation
     */
    public function attachments()
    {
        return $this->hasMany(QuotationAttachment::class);
    }

    /**
     * Get company of the quotation
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get revisions of quotation
     */
    public function revisions()
    {
        return $this->hasMany(QuotationRevision::class);
    }

    /**
     * Get invoice of quotation
     */
    public function invoice()
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }

    /**
     * Get all possible quotation types as array
     * 
     * @static
     * @return array
     */
    public static function getTypeValues()
    {
        return Type::getValues();
    }

    /**
     * Get all possible status values as array
     * 
     * @static
     * @return array
     */
    public static function getStatusValues()
    {
        return Status::getValues();
    }

    /**
     * Get all possible payment method values as array
     * 
     * @static
     * @return array
     */
    public static function getPaymentMethodValues()
    {
        return PaymentMethod::getValues();
    }

    /**
     * Get all canceller values as array
     * 
     * @static
     * @return array
     */
    public static function getCancellerValues()
    {
        return Canceller::getValues();
    }

    /**
     * Collect all possible quotation types enums as array
     * 
     * @static
     * @return array
     */
    public static function collectAllTypes()
    {
        return Type::asSelectArray();
    }

    /**
     * Collect all possible quotation statuses enums as array
     * 
     * @static
     * @return array
     */
    public static function collectAllStatuses()
    {
        return Status::asSelectArray();
    }

    /**
     * Collect all possible quotation payment methods enums as array
     * 
     * @static
     * @return array
     */
    public static function collectAllPaymentMethods()
    {
        return PaymentMethod::asSelectArray();
    }

    /**
     * Collect all possible quotation damage causes enums as array
     * 
     * @static
     * @return array
     */
    public static function collectAllDamageCauses()
    {
        return DamageCause::asSelectArray();
    }

    /**
     * Collect all possible canceller as array
     * 
     * @static
     * @return array
     */
    public static function collectAllCanceller()
    {
        return Canceller::asSelectArray();
    }

    /**
     * Count total of quotation works amount
     * 
     * @return double
     */
    public function countWorksAmount()
    {
        $total = $this->works()->sum('total_price');
        $this->setAmountAttribute($total);
        return $total;
    }

    /**
     * Calculate total by adding amount with VAT amount 
     * and substrating with discount amount
     * 
     * @return double
     */
    public function calculateTotal()
    {
        $amount = $this->attributes['amount'];
        $vatAmount = $this->getVatAmountAttribute();
        $discountAmount = $this->attributes['discount_amount'];

        return $this->attributes['total_amount'] = $amount + $vatAmount - $discountAmount;
    }
}