<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

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
    use CastsEnums;

    protected $table = 'quotations';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

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
        'quotation_document_url',
        'is_signed',

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
    	});

        self::saving(function ($quotation) {
            $quotation->total_amount = $quotation->amount - $quotation->discount_amount;
        });
    }

    public function getTypeDescriptionAttribute()
    {
        return QuotationType::getDescription($this->attributes['type']);
    }

    public function getFormattedAmountAttribute()
    {
        setlocale(LC_MONETARY, 'nl_NL.UTF-8');
        return money_format('%(#1n', $this->attributes['amount']);
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

        setlocale(LC_MONETARY, 'nl_NL.UTF-8');
        return money_format('%(#1n', $vatAmount);
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

    public function setDocumentAttribute($document)
    {
        $path = 'storage/uploads/quotations/files/';
        $documentPath = uploadFile($document, $path);
        $pdfUrl = asset($documentPath);

        return $this->attributes['quotation_document_url'] = $pdfUrl;
    }

    public function setDiscountPercentageAttribute(string $percentage)
    {
        $percentage = str_replace('%', '', $percentage);
        $percentage = (float) $percentage;

        $amount = $this->attributes['amount'];
        $this->attributes['discount_amount'] = $amount * $percentage;
    }

    public function appointment()
    {
        return $this->belongsTo(
            'App\Models\Appointment', 
            'appointment_id',
            'id',
        );
    }

    public function customer()
    {
        return $this->hasOne(
            'App\Models\Customer', 
            'id',
            'customer_id'
        );
    }

    public function photos()
    {
        return $this->hasMany(
            'App\Models\QuotationPhoto',
            'quotation_id',
            'id'
        );
    }

    public function company()
    {
        return $this->belongsTo(
            'App\Models\Company', 
            'company_id', 
            'id'
        );
    }

    public function revisions()
    {
        return $this->hasMany(
            'App\Models\QuotationRevision',
            'quotation_id',
            'id'
        );
    }

    public static function getTypeValues()
    {
        $collection = collect(static::TYPES);
        $types = $collection->pluck(['value']);

        return $types->toArray();
    }

    public static function getStatusValues()
    {
        return $statuses;
    }

    public static function getPaymentMethodValues()
    {
        $collection = collect(static::PAYMENT_METHODS);
        $methods = $collection->pluck('value');

        return $methods->toArray();
    }

    public static function getCancellerValues()
    {
        $collection = collect(static::CANCELLERS);
        $cancellers = $collection->pluck('value');

        return $cancellers->toArray();
    }
}