<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

use App\Enums\Appointment\AppointmentType;
use App\Enums\Appointment\AppointmentStatus;
use App\Enums\Appointment\AppointmentCancellationVault;

class Appointment extends Model
{
    use SoftDeletes;
    
    protected $table = 'appointments';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'customer_id',

        'start',
        'end',
        'include_weekend',

        'previous_appointment_id',
        'next_appointment_id',

        'cancellation_cause',
        'cancellation_vault',
        'cancellation_note',

        'status',
        'type',
        
        'note',
    ];

    protected $casts = [
        'include_weekend' => 'boolean',
        'start' => 'datetime',
        'end' => 'datetime',

        'status' => AppointmentStatus::class,
        'type' => AppointmentType::class,
        'cancellation_vault' => AppointmentCancellationVault::class,
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($appointment) {
            $appointment->id = Uuid::generate()->string;
    	});
    }

    public function setAppointmentStatusAttribute($status)
    {
        if (! is_int($status)) {
            $status = AppointmentStatus::getValue($status);
        }

        $this->attributes['status'] = $status;
    }

    public function setAppointmentTypeAttribute($type)
    {
        if (! is_int($type)) {
            $type = AppointmentType::getValue($type);
        }

        $this->attributes['type'] = $type;
    }

    public function setAppointmentCancellationVaultAttribute($vault)
    {
        if (! is_int($vault)) {
            $vault = AppointmentCancellationVault::getValue($vault);
        }

        $this->attributes['cancellation_vault'] = $vault;
    }

    public function getTypeDescriptionAttribute()
    {
        $typeCode = $this->attributes['type'];
        return AppointmentType::getDescription($typeCode);
    }

    public function getStatusDescriptionAttribute()
    {
        $statusCode = $this->attributes['status'];
        return AppointmentStatus::getDescription($statusCode);
    }

    public function getCancellationVaultDescriptionAttribute()
    {
        $cancellationVaultCode = $this->attributes['cancellation_vault'];
        return AppointmentCancellationVault::getDescription($cancellationVaultCode);
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id', 'id');
    }

    public function customer()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }

    public function subs()
    {
        return $this->hasMany('App\Models\SubAppointment', 'appoinment_id', 'id');
    }

    public function inspection()
    {
        return $this->hasOne('App\Models\Inspection', 'appointment_id', 'id');
    }

    public function quotation()
    {
        return $this->hasOne('App\Models\Quotation', 'appointment_id', 'id');
    }

    public function executeWork()
    {
        return $this->hasOne('App\Models\ExecuteWork', 'appointment_id', 'id');
    }

    public function warranty()
    {
        return $this->hasOne('App\Models\Warranty', 'appointment_id', 'id');
    }

    public function paymentReminder()
    {
        return $this->hasOne('App\Models\Warranty', 'appointment_id', 'id');
    }

    public static function typeOptions()
    {
        return AppointmentType::asSelectArray();
    }

    public static function statusOptions()
    {
        return AppointmentStatus::asSelectArray();
    }

    public static function cancellationVaultOptions()
    {
        return AppointmentCancellationVault::asSelectArray();
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
        $this->setStatusAttribute('InProcess');
        $this->attributes['in_process_at'] = carbon()->now();
        return $this->save();
    }

    public function process()
    {
        $this->setStatusAttribute('processed');
        $this->attributes['processed_at'] = carbon()->now();
        return $this->save();
    }

    public function cancel()
    {
        $this->setStatusAttribute('cancelled');
        $this->attributes['cancelled_at'] = carbon()->now();
        return $this->save();
    }
}