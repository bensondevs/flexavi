<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use BenSampo\Enum\Traits\CastsEnums;

use App\Casts\QuotationDamageCausesCast;

use App\Enums\Quotation\QuotationType;
use App\Enums\Quotation\QuotationStatus;
use App\Enums\Quotation\QuotationCanceller;
use App\Enums\Quotation\QuotationDamageCause;
use App\Enums\Quotation\QuotationPaymentMethod;

use App\Models\Inspection;

class Quotation extends Model
{
    use SoftDeletes;
    use Searchable;
    use CastsEnums;

    protected $table = 'quotations';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'quotation_number',
        'contact_person',
        'address',
        'zip_code',
        'address',
        'phone_number',
        'quotation_description',
        'honor_note',
        'cancellation_reason',
    ];

    protected $fillable = [
        'company_id',
        'customer_id',
        'appointment_id',
        
        'type',

        'quotation_date',
        'quotation_number',
        
        'contact_person',

        'address',
        'zip_code',
        'address',
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

    protected $cast = [
        'type' => QuotationType::class,
        'damage_causes' => QuotationDamageCausesCast::class,
        'payment_method' => QuotationPaymentMethod::class,
        'is_signed' => 'boolean',
        'honored_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($quotation) {
            $quotation->id = Uuid::generate()->string;

            if (! $quotation->expiry_date) {
                $expiryDate = carbon()->now()->addDays(14);
                $quotation->expiry_date = $expiryDate;
            }
    	});

        /*self::saved(function ($quotation) {
            $quotation->countWorkAmount();
        });*/
    }

    public function getTypeDescriptionAttribute()
    {
        return QuotationType::getDescription($this->attributes['type']);
    }

    public function getFormattedAmountAttribute()
    {
        return currency_format($this->attributes['amount']);
    }

    public function getFormattedVatPercentageAttribute()
    {
        return $this->attributes['vat_percentage'] . '%';
    }

    public function getVatAmountAttribute()
    {
        $amount = $this->attributes['amount'];
        $percentage = $this->attributes['vat_percentage'];

        return ($percentage / 100) * $amount;
    }

    public function getFormattedVatAmountAttribute()
    {
        $vatAmount = $this->getVatAmountAttribute();

        return currency_format($vatAmount);
    }

    public function getFormattedExpiryDateAttribute()
    {
        $expiryDate = $this->attributes['expiry_date'];

        return carbon($expiryDate)->format('M d, Y');
    }

    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];

        return QuotationStatus::getDescription($status);
    }

    public function getPaymentMethodDescriptionAttribute()
    {
        $method = $this->attributes['payment_method'];

        return QuotationPaymentMethod::getDescription($method);
    }

    public function getCancellerDescriptionAttribute()
    {
        $canceller = $this->attributes['canceller'];

        return QuotationCanceller::getDescription($canceller);
    }

    public function setDamageCausesAttribute(array $damageCauses = [])
    {
        $this->attributes['damage_causes'] = json_encode($damageCauses);
    }

    public function setDiscountPercentageAttribute(string $percentage)
    {
        $percentage = str_replace('%', '', $percentage);
        $percentage = (float) $percentage;

        $amount = $this->attributes['amount'];
        $this->attributes['discount_amount'] = $amount * $percentage;
    }

    public function setAmountAttribute($amount)
    {
        $this->attributes['amount'] = $amount;
        $vatAmount = $this->getVatAmountAttribute();
        $this->calculateTotal();
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function works()
    {
        return $this->morphedByMany(Work::class, 'workable');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function attachments()
    {
        return $this->hasMany(QuotationAttachment::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function revisions()
    {
        return $this->hasMany(QuotationRevision::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public static function getTypeValues()
    {
        return QuotationType::getValues();
    }

    public static function getStatusValues()
    {
        return QuotationStatus::getValues();
    }

    public static function getPaymentMethodValues()
    {
        return QuotationPaymentMethod::getValues();
    }

    public static function getCancellerValues()
    {
        return QuotationCanceller::getValues();
    }

    public function countWorksAmount()
    {
        $total = db('works')
            ->where('quotation_id', $this->attributes['id'])
            ->sum('works.total_price');
        $this->setAmountAttribute($total);
    }

    public function calculateTotal()
    {
        $amount = $this->attributes['amount'];
        $vatAmount = $this->getVatAmountAttribute();
        $discountAmount = $this->attributes['discount_amount'];

        return $this->attributes['total_amount'] = $amount + $vatAmount - $discountAmount;
    }

    public static function collectAllTypes()
    {
        return QuotationType::asSelectArray();
    }

    public static function collectAllStatuses()
    {
        return QuotationStatus::asSelectArray();
    }

    public static function collectAllPaymentMethods()
    {
        return QuotationPaymentMethod::asSelectArray();
    }

    public static function collectAllDamageCauses()
    {
        return QuotationDamageCause::asSelectArray();
    }

    public static function collectAllCanceller()
    {
        return QuotationCanceller::asSelectArray();
    }
}