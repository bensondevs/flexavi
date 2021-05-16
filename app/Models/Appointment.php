<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    const TYPES = [
        [
            'label' => 'Inspection',
            'value' => 'inspection',
            'model' => 'App\Models\Inspection',
        ],
        [
            'label' => 'Quotation',
            'value' => 'quotation',
            'model' => 'App\Models\Quotation',
        ],
        [
            'label' => 'Work',
            'value' => 'work',
            'model' => 'App\Models\Work',
        ],
        [
            'label' => 'Warranty Claim',
            'value' => 'warranty_claim',
            'model' => 'App\Models\WarrantyClaim',
        ],
        [
            'label' => 'Payment Term',
            'value' => 'payment_term',
            'model' => 'App\Models\PaymentTerm',
        ]
    ];

    const STATUSES = [
        [
            'label' => 'Created',
            'value' => 'created',
        ],
        [
            'label' => 'Updated',
            'value' => 'updated',
        ],
        [
            'label' => 'Processed',
            'value' => 'processed',
        ],
        [
            'label' => 'Finished',
            'value' => 'finished',
        ]
    ];

    protected $fillable = [
        'company_id',
        'customer_id',

        'start',
        'end',
        'include_weekend',

        'appointment_type',
        'appointment_status',
        
        'note',
    ];

    protected $casts = [
        'include_weekend' => 'boolean',
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($appointment) {
            $appointment->id = Uuid::generate()->string;
    	});
    }

    public function appointmentable()
    {
        return $this->hasOne(
            'App\Models\Appointmentable',
            'id',
            'appointment_id'
        );
    }

    public function customer()
    {
        return $this->hasOne(
            'App\Models\Customer',
            'customer_id',
            'id'
        );
    }

    public static function getTypes()
    {
        $value = collect(self::TYPES)->pluck('value');
            
        return $value->all();
    }

    public static function getStatuses()
    {
        $value = collect(self::STATUSES)->pluck('value');
            
        return $value->all();
    }
}