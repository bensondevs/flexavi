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

        'cancellation_cause',

        'appointment_status',
        'appointment_type',
        
        'note',
    ];

    protected $casts = [
        'include_weekend' => 'boolean',
        'start' => 'datetime',
        'end' => 'datetime',
        'is_late' => 'boolean',
        'cancelled' => 'boolean',
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

        return $this->hasOne($type['model'], 'appointment_id', 'id');
    }

    public function customer()
    {
        return $this->hasOne(
            'App\Models\Customer',
            'customer_id',
            'id'
        );
    }

    public function setStatusAttribute($status)
    {
        // To be mofidied
        $this->attributes['appointment_type'] = $status;
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
        return $types->pluck('value')->toArray();
    }

    public static function getStatusValues()
    {
        $statuses = collect(self::STATUSES);
        return $statuses->pluck('value')->toArray();
    }

    public function isLate()
    {
        $end = carbon()->parse($this->attributes['end']);
        $now = carbon()->now();
        return ($now > $end);
    }

    public function isOnTime()
    {
        $end = carbon()->parse($this->attributes['end']);
        $now = carbon()->now();
        return ($now < $end);
    }

    public function execute()
    {
        $this->setStatusAttribute('in_process');
        $this->attributes['is_late'] = $this->isLate();
        $this->attributes['cancelled'] = $this->isLate();
        return $this->save();
    }

    public function process()
    {
        $this->setStatusAttribute('processed');
        $this->attributes['is_late'] = $this->isLate();
        $this->attributes['cancelled'] = $this->isLate();
        return $this->save();
    }
}