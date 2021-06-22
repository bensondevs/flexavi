<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class SubAppointment extends Model
{
    protected $table = 'sub_appointments';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

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
            'label' => 'Cancelled',
            'value' => 'cancelled',
        ]
    ];

    const VAULTS = [
        [
            'label' => 'Roofer',
            'value' => 'roofer',
        ],
        [
            'label' => 'Customer',
            'value' => 'customer',
        ]
    ];

    protected $fillable = [
        'appointment_id',

        'previous_sub_appointment_id',
        'rescheduled_sub_appointment_id',

        'status',
        'start',
        'end',

        'cancellation_cause',
        'cancellation_vault',
        'cancellation_note',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($subAppointment) {
            $subAppointment->id = Uuid::generate()->string;
    	});
    }

    public function appointment()
    {
        return $this->belongsTo(
            'App\Models\Appointment', 
            'appoinment_id', 
            'id'
        );
    }

    public function previousSubAppointment()
    {
        return $this->belongsTo(
            'App\Models\Appointment', 
            'appoinment_id', 
            'id'
        );
    }

    public static function getStatusValues()
    {
        $statuses = collect(self::STATUSES);
        return $statuses->pluck('value')->toArray();
    }

    public static function getVaultValues()
    {
        $vaults = collect(self::VAULTS);
        return $vaults->pluck('value')->toArray();
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
}