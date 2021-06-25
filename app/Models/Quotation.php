<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Traits\ModelEnums;

use App\Models\Inspection;

class Quotation extends Model
{
    use ModelEnums;

    protected $table = 'quotations';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    const TYPES = [
        'Leakage',
        'Renovation',
        'Reparation',
        'Renewal',
    ];

    const STATUSES = [
        'Draft',
        'Send',
        'Revised',
        'Honored',
        'Cancelled',
    ];

    const PAYMENT_METHODS = [
        'cash',
        'bank',
    ];

    const CANCELLERS = [
        'company',
        'customer',
    ];

    protected $fillable = [
        'company_id',
        'creator_id',
        'customer_id',
        'appointment_id',
        
        'subject',
        
        'quotation_number',
        'quotation_type',
        'quotation_description',

        'quotation_document_url',
        'expiry_date',
        'status',
        'payment_method',

        'amount',
        'discount_amount',
        'total_amount',

        'honor_note',
        'honored_at',

        'canceller',
        'cancellation_reason',
    ];

    protected $casts = [
        'works' => 'array'
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
        return $this->hasOne(
            'App\Models\Appointment', 
            'appoinment_id', 
            'id'
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

    public function creator()
    {
        return $this->belongsTo(
            'App\Models\User', 
            'creator_id', 
            'id'
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