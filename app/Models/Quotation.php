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
        [
            'label' => 'Leakage',
            'value' => 'leakage',
        ],
        [
            'label' => 'Renovation',
            'value' => 'renovation',
        ],
        [
            'label' => 'Reparation',
            'value' => 'reparation',
        ],
        [
            'label' => 'Renewal',
            'value' => 'renewal',
        ]
    ];

    const STATUSES = [
        [
            'label' => 'Draft',
            'value' => 'draft',
        ],
        [
            'label' => 'Send',
            'value' => 'send',
        ],
        [
            'label' => 'Revised',
            'value' => 'revised',
        ],
        [
            'label' => 'Honored',
            'value' => 'honored',
        ],
        [
            'label' => 'Cancelled',
            'value' => 'cancelled',
        ]
    ];

    const PAYMENT_METHODS = [
        [
            'label' => 'Cash',
            'value' => 'cash',
        ],
        [
            'label' => 'Credit Card',
            'value' => 'credit_card',
        ],
        [
            'label' => 'Paypal',
            'value' => 'paypal',
        ]
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
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($quotation) {
            $quotation->id = Uuid::generate()->string;
    	});
    }

    public function setDocumentAttribute($document)
    {
        $path = 'storage/uploads/quotations/files/';
        $documentPath = uploadFile($document, $path);
        $pdfUrl = asset($documentPath);

        return $this->attributes['quotation_document_url'] = $pdfUrl;
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

    public static function getTypes()
    {
        $collection = collect(static::TYPES);
        $types = $collection->pluck(['value']);

        return $types->toArray();
    }

    public static function getStatuses()
    {
        $collection = collect(static::STATUSES);
        $statuses = $collection->pluck(['value']);

        return $statuses->toArray();
    }
}