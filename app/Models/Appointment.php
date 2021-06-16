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
            'label' => 'Execute Work',
            'value' => 'work',
            'model' => 'App\Models\Work',
        ],
        [
            'label' => 'Warranty',
            'value' => 'warranty',
            'model' => 'App\Models\Warranty',
        ],
        [
            'label' => 'Payment Pickup',
            'value' => 'payment_pickup',
            'model' => 'App\Models\PaymentPickup',
        ],
        [
            'label' => 'Payment Reminder',
            'value' => 'payment_reminder',
            'model' => 'App\Models\PaymentReminder',
        ]
    ];

    const STATUSES = [
        [
            'label' => 'Created',
            'value' => 'created',
        ],
        [
            'label' => 'In Process',
            'value' => 'in_process',
        ],
        [
            'label' => 'Processed',
            'value' => 'processed',
        ],
        [
            'label' => 'Calculated',
            'value' => 'calculated',
        ]
    ];

    protected $fillable = [
        'company_id',
        'customer_id',

        'start',
        'end',
        'include_weekend',

        'appointment_status',
        'appointment_type',
        
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

    public function assigned()
    {
        $appointmentType = $this->attributes['appointment_type'];
        $types = collect(self::TYPES);
        $type = $types->where('value', $appointmentType)->first();
        $type = $type->toArray();

        return $this->hasOne($type['model'], 'id', 'appointment_type_id');
    }

    public function customer()
    {
        return $this->hasOne(
            'App\Models\Customer',
            'customer_id',
            'id'
        );
    }

    public function getTypeLabelAttribute()
    {
        $types = collect(self::TYPES);
        $type = $this->attributes['type'];
        
        if (! $_type = $types->where('value', $type)->first())
            return null;

        return $_type['label'];
    }

    public static function getTypeValues()
    {
        $types = collect(self::TYPES);
            
        return $type->pluck('value');
    }

    public static function getStatusValues()
    {
        $statuses = collect(self::STATUSES);
            
        return $statuses->pluck('value');
    }
}